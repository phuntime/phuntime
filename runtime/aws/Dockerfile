FROM amazonlinux:2018.03
#
# AWS Lambda runs on Amazon Linux 2018, so all custom runtimes must be compiled on this version
#

ARG PHP_VERSION
ARG BISON_VERSION
ARG SWOOLE_VERSION

# Update packages and install needed compilation dependencies
RUN yum update -y
RUN yum clean all
RUN yum install readline-devel openssl-devel libcurl-devel glibc zlib tar gzip cmake texinfo makeinfo help2man autoconf gcc gcc-c++ libcurl-devel libxml2-devel re2c sqlite-devel -y

#where we are building things
ENV BUILD_DIR="/tmp/build"

ENV INI_FILE_LOCATION="/opt/php/lib/php.ini"
ENV FPM_CONF_LOCATION="/opt/php/etc/php-fpm.conf"

#start in root
WORKDIR ${BUILD_DIR}

#
# bison 3.0.0 or later is required to generate PHP parsers
# There is only 2.7 available in yum, so we need to install a fresh one manually
#
RUN curl -vsL http://ftp.gnu.org/gnu/bison/bison-${BISON_VERSION}.tar.gz | tar -xvz
WORKDIR ${BUILD_DIR}/bison-${BISON_VERSION}
RUN ./configure && make && make install
RUN ln -s /usr/local/bin/bison /usr/bin/bison

# verify bison build
RUN bison --help

#Back to build root
WORKDIR ${BUILD_DIR}

# Download the PHP source
RUN curl -vsL https://github.com/php/php-src/archive/php-${PHP_VERSION}.tar.gz | tar -xvz
WORKDIR ${BUILD_DIR}/php-src-php-${PHP_VERSION}
RUN ./buildconf --force
RUN EXTENSION_DIR=/opt/php/ext ./configure --prefix=/opt/php \
    --enable-fpm \
    --with-openssl \
    --with-curl \
#    --with-zip \ #TODO add libzip
#    --with-sodium \ #TODO add libsodium
    --with-gettext \
#    --with-gmp \ #TODO add GNU MP
    --with-zlib \
    --disable-short-tags \
    --without-pear \
    --with-readline \
    --with-config-file-path=/opt/php \
    --enable-pcntl


RUN make -j $(nproc) && make install

#verify php build
RUN /opt/php/bin/php -v

# add php binaries to PATH
ENV PATH="/opt/php/bin:/opt/php/sbin:${PATH}"

# add php-fpm config
COPY ./php-fpm.conf ${FPM_CONF_LOCATION}

# install swoole
WORKDIR ${BUILD_DIR}
RUN curl -vsL https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz | tar -xvz
WORKDIR ${BUILD_DIR}/swoole-src-${SWOOLE_VERSION}
RUN phpize && ./configure && make && make install
RUN echo "extension=swoole.so" >> ${INI_FILE_LOCATION}


