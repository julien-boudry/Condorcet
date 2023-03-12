Init Docker buildx:
```shell
docker buildx create --name mybuilder --use --bootstrap
```

Build & Pull:
```shell
docker buildx build --push --platform linux/arm64/v8,linux/amd64,linux/mips64le,linux/ppc64le --tag julienboudry/condorcet:vX.x .
```