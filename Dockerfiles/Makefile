REPO_OWNER:=NARKOZ
PROJECT:=hacker-scripts
MULTIARCH:=false
ARCHS:=linux/amd64
VERSION:=''
ALPINE:=false
ifeq (true, $(MULTIARCH))
	ARCHS:=linux/amd64,linux/arm/v6,linux/arm/v7,linux/arm64/v8,linux/ppc64le,linux/s390x
endif

all: setup build

setup:
	@./buildx.sh

build: fucking-coffe hangover kumar-asshole smack-my-bitch-up

fucking-coffe:
	@docker buildx build \
	$(ENV) \
	--file ./nodejs/Dockerfile.fucking_coffe \
	--platform $(ARCHS) \
	--push --tag $(REPO_OWNER)/$(PROJECT):fucking-coffe$(VERSION) \
	../nodejs

hangover:
	@docker buildx build \
	$(ENV) \
	--file ./nodejs/Dockerfile.hangover \
	--platform $(ARCHS) \
	--push --tag $(REPO_OWNER)/$(PROJECT):hangover$(VERSION) \
	../nodejs

kumar-asshole:
	@docker buildx build \
	$(ENV) \
	--file ./nodejs/Dockerfile.kumar_asshole \
	--platform $(ARCHS) \
	--push --tag $(REPO_OWNER)/$(PROJECT):kumar-asshole$(VERSION) \
	../nodejs

smack-my-bitch-up:
	@docker buildx build \
	$(ENV) \
	--file ./nodejs/Dockerfile.smack_my_bitch_up \
	--platform $(ARCHS) \
	--push --tag $(REPO_OWNER)/$(PROJECT):smack-my-bitch-up$(VERSION) \
	../nodejs
