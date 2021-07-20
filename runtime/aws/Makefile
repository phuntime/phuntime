#aliases for AWS SAM build process
build-RuntimeLayer: build-runtime
build-FpmRuntimeLayer: build-fpm-runtime

build-runtime:
ifndef ARTIFACTS_DIR
	$(error ARTIFACTS_DIR is undefined)
endif
	PHT_RUNTIME_DIR=$(ARTIFACTS_DIR) PHP_VERSION=8.0.7 ./build-aws-runtime.sh

# ARTIFACTS_DIR = Where the files will be deployed
# _PWD = Path to project root (AWS SAM are using tmp dir while building so its impossible to get phuntime files)
build-fpm-runtime:
ifndef ARTIFACTS_DIR
	$(error ARTIFACTS_DIR is undefined)
endif
ifndef _PWD
	$(error _PWD is undefined)
endif
    #build default runtime
	PHT_RUNTIME_DIR=$(ARTIFACTS_DIR) ./build-aws-runtime.sh
	#override bootstrap file
	cp fpm-bootstrap ${ARTIFACTS_DIR}/bootstrap
	chmod +x ${ARTIFACTS_DIR}/bootstrap
	#copy contents to runtime, create fresh prod vendors
	cp ${_PWD}/composer.json ${ARTIFACTS_DIR}/composer.json
	cp ${_PWD}/composer.lock ${ARTIFACTS_DIR}/composer.lock
	mkdir -p ${ARTIFACTS_DIR}/src/Phuntime
	cp -r ${_PWD}/src/Phuntime/Core ${ARTIFACTS_DIR}/src/Phuntime
	cp -r ${_PWD}/src/Phuntime/Aws ${ARTIFACTS_DIR}/src/Phuntime
	(cd ${ARTIFACTS_DIR} && composer install --no-dev)
