.PHONY: test test-unit test-feature build shell build-dev serve stop shell-dev logs

IMAGE     := jwtauth-test
IMAGE_DEV := jwtauth-dev

build:
	podman build -t $(IMAGE) -f Containerfile .

test: build
	podman run --rm $(IMAGE)

test-unit: build
	podman run --rm $(IMAGE) \
		php artisan winter:test -p RLuders.JWTAuth --testsuite=Unit

test-feature: build
	podman run --rm $(IMAGE) \
		php artisan winter:test -p RLuders.JWTAuth --testsuite=Feature

shell: build
	podman run --rm -it $(IMAGE) sh

build-dev:
	podman build -t $(IMAGE_DEV) -f Containerfile.dev .

serve: build-dev
	podman run -d --rm -p 8080:80 --name jwtauth-dev $(IMAGE_DEV)
	@echo "Running at http://localhost:8080 — stop with: make stop"

stop:
	podman stop jwtauth-dev

logs:
	podman logs -f jwtauth-dev

shell-dev: build-dev
	podman run --rm -it -p 8080:80 $(IMAGE_DEV) sh
