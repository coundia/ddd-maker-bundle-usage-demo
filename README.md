# Options 1 Run with Docker

```
composer install
```

```
docker compose up -d --build
```

# Options 2 Run without Docker

```
composer install
```
```
cp .env.example .env
symfony serve
```

# Options 2 if you have  task go (optional)
https://taskfile.dev/installation/

```
task start

```
### Api docs
[http://127.0.0.1:8000/api/docs](http://127.0.0.1:8000/api/docs)

### See 

[doc/usage.md](doc/usage.md)