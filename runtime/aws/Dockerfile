FROM amazonlinux:2018.03
#
# AWS Lambda runs on Amazon Linux 2018, so all custom runtimes must be compiled on this version
#

# Update packages and install needed compilation dependencies
RUN yum update -y
RUN yum clean all
RUN yum install openssl-devel libcurl-devel glibc zlib tar gzip cmake texinfo makeinfo help2man autoconf gcc gcc-c++ libcurl-devel libxml2-devel re2c sqlite-devel -y

#where we are building things
ENV BUILD_DIR="/tmp/build"
#path to php.ini
ENV INI_FILE_LOCATION="/opt/php/lib/php.ini"

ENV BISON_VERSION="3.4"
ENV PHP_VERSION="7.4.8"
ENV SWOOLE_VERSION="4.5.2"

#start in root
WORKDIR ${BUILD_DIR}

#
# bison 3.0.0 or later is required to generate PHP parsers
# There is only 2.7 available in yum, so we need to install a fresh one manually
#
RUN curl -sL http://ftp.gnu.org/gnu/bison/bison-${BISON_VERSION}.tar.gz | tar -xvz
WORKDIR ${BUILD_DIR}/bison-${BISON_VERSION}
RUN ./configure && make && make install
RUN ln -s /usr/local/bin/bison /usr/bin/bison

# verify bison build
RUN bison --help

#Back to build root
WORKDIR ${BUILD_DIR}

# Download the PHP source
RUN curl -sL https://github.com/php/php-src/archive/php-${PHP_VERSION}.tar.gz | tar -xvz
WORKDIR ${BUILD_DIR}/php-src-php-${PHP_VERSION}
RUN ./buildconf --force
RUN ./configure --prefix=/opt/php/ \
    --enable-fpm \
    --with-openssl \
    --with-curl \
    --with-zlib \
    --disable-short-tags \
    --without-pear

RUN make -j $(nproc) && make install

#verify php build
RUN /opt/php/bin/php -v

# add php binaries to PATH
ENV PATH="/opt/php/bin:/opt/php/sbin:${PATH}"

# install swoole
WORKDIR ${BUILD_DIR}
RUN curl -sL https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz | tar -xvz
WORKDIR ${BUILD_DIR}/swoole-src-${SWOOLE_VERSION}
RUN phpize && ./configure && make && make install
RUN echo "extension=swoole.so" >> ${INI_FILE_LOCATION}
#check for swoole
RUN /opt/php/bin/php -i | grep swoole