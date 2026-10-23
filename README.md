# Oak Street Cycles — PHP 8.4.26 vs 8.4.27

The same small bike-shop booking site, built twice: once on the affected PHP **8.4.26** and once on the patched PHP **8.4.27**. Same code, same PHP configuration, same Apache setup. The only difference is the PHP version.

## Start

```bash
docker compose up --build
```

Then open:

- **http://127.0.0.1:8080** — PHP 8.4.26 (affected)
- **http://127.0.0.1:8081** — PHP 8.4.27 (patched)

Stop everything with:

```bash
docker compose down
```

Confirm the versions:

```bash
docker compose exec vulnerable php -v
docker compose exec patched php -v
```

## What this is

A simple way to run the two runtimes side by side and test them yourself. Both containers serve identical files, so anything that behaves differently comes from PHP, not from the application.

## Notes

- Both sites bind to `127.0.0.1` only.
- Images are `linux/amd64`. On Apple Silicon, enable emulation in Docker Desktop or change `platform` in `compose.yaml`.
- Ports `8080` / `8081` can be changed in `compose.yaml` if they are already in use.
- The 8.4.26 image is an intentionally affected build for testing and research. Keep it local.
