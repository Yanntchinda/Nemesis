#!/usr/bin/env python3
"""Serveur de préview sans cache : force le rechargement des fichiers à chaque requête."""
import http.server
import socketserver

class NoCacheHandler(http.server.SimpleHTTPRequestHandler):
    def end_headers(self):
        self.send_header("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0")
        self.send_header("Pragma", "no-cache")
        self.send_header("Expires", "0")
        super().end_headers()

    def _drop_conditional(self):
        # Empêche toute réponse 304 : le fichier servi est toujours le courant
        for h in ("If-Modified-Since", "If-None-Match"):
            while h in self.headers:
                del self.headers[h]

    def do_GET(self):
        self._drop_conditional()
        super().do_GET()

    def do_HEAD(self):
        self._drop_conditional()
        super().do_HEAD()

with socketserver.TCPServer(("0.0.0.0", 8080), NoCacheHandler) as httpd:
    print("Serving on 0.0.0.0:8080 (no-cache)")
    httpd.serve_forever()
