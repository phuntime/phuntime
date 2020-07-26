build-RuntimeLayer: build-runtime
build-FpmRuntimeLayer: build-fpm-runtime

build-runtime:
ifndef ARTIFACTS_DIR
	$(error ARTIFACTS_DIR is undefined)
endif
	PHT_RUNTIME_DIR=$(ARTIFACTS_DIR) ./build-aws-runtime.sh

build-fpm-runtime:
ifndef ARTIFACTS_DIR
	$(error ARTIFACTS_DIR is undefined)
endif
	PHT_RUNTIME_DIR=$(ARTIFACTS_DIR) ./build-aws-runtime.sh -f