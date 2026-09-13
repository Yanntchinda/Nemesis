#!/usr/bin/env python3
"""Static preview server for the Marine + multi-page site.

Serves the repository directory on 0.0.0.0:8080; "/" resolves to
index.html via SimpleHTTPRequestHandler's default directory index.
Kept inside the repo so it survives sandbox re-provisioning.

Usage: python3 preview_server.py [port]
"""
import http.server
import os
import socketserver
import sys

ROOT = os.path.dirname(os.path.abspath(__file__))
PORT = int(sys.argv[1]) if len(sys.argv) > 1 else int(os.environ.get("PORT", "8080"))


class Handler(http.server.SimpleHTTPRequestHandler):
    def __init__(self, *args, **kwargs):
        super().__init__(*args, directory=ROOT, **kwargs)

    def end_headers(self):
        # No X-Frame-Options / CSP frame-ancestors: the preview is embedded
        # in an iframe on a different origin, so framing must stay allowed.
        self.send_header("Cache-Control", "no-store")
        super().end_headers()

    def log_message(self, fmt, *args):
        print("%s - %s" % (self.address_string(), fmt % args), flush=True)


class Server(socketserver.ThreadingTCPServer):
    allow_reuse_address = True
    daemon_threads = True


if __name__ == "__main__":
    with Server(("0.0.0.0", PORT), Handler) as httpd:
        print(f"Serving {ROOT} on 0.0.0.0:{PORT}", flush=True)
        httpd.serve_forever()
