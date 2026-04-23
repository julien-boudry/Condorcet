Init Docker buildx:
```shell
docker buildx create --name mybuilder --use --bootstrap
```

Build & Pull:
```shell
docker buildx build --push --platform linux/arm64/v9,linux/arm64/v8,linux/arm/v7,linux/amd64,linux/riscv64,linux/ppc64le --tag julienboudry/condorcet:vX.x .
```