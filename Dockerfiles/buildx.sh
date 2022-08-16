#!/bin/sh
export DOCKER_CLI_EXPERIMENTAL=enabled
export DOCKER_BUILDKIT=1

docker build --platform=local -o . git://github.com/docker/buildx
mkdir -p ~/.docker/cli-plugins
mv buildx ~/.docker/cli-plugins/docker-buildx
docker run --rm --privileged multiarch/qemu-user-static --reset -p yes
docker buildx create --name builder --driver docker-container --use

# https://github.com/docker/docker-ce/blob/master/components/cli/experimental/README.md
sudo printf "{\n\
\t\"experimental\": true\n\
}\n" | sudo tee /etc/docker/daemon.json

SHELL_RC="/dev/null"

if [[ "zsh" == ${SHELL} ]]; then
    SHELL_RC="/.zshrc"
fi
if [[ "bash" == ${SHELL} ]]; then
    SHELL_RC="/.bashrc"
fi

printf "\n\
# Docker's buildx support\n\
export DOCKER_CLI_EXPERIMENTAL=enabled\n\
export DOCKER_BUILDKIT=1\n\
" >> $HOME$SHELL_RC
