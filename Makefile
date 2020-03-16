server ?= docker

build:
	docker build -t lol-parser -f Dockerfile .

start: stop
	# Queue
	docker run -d --name lol-queue rabbitmq:latest

	# Database
	docker run -d --name lol-db -e "MYSQL_ROOT_PASSWORD=4wV*4ynnCMXj" mysql:latest

	# Parser
	docker run -d -p 80:80 --name lol-parser \
		--link lol-queue:rmq.local \
		--link lol-db:mysql.local \
		lol-parser

test:
	bin/phpspec r

stop:
	@docker rm -vf lol-parser lol-queue lol-db||:

clean: stop
	docker rmi -f lol-parser rabbitmq mysql


rsync:
ifneq ($(wildcard vendor ),)
	$(info Vendor exists, including it)
	$(eval RSYNC_VENDOR := --include=vendor --filter="+ vendor")
endif
	@printf "lol-parser" | xargs -n1 -P1 -ICONTAINER rsync \
		-e "docker exec -i" --blocking-io -avz --delete \
		--no-perms --no-owner --no-group \
		$(RSYNC_VENDOR) \
		--exclude-from=".dockerignore" \
		--exclude-from=".gitignore" \
		--checksum \
		--no-times \
		--itemize-changes \
		. CONTAINER:/home/sites/lol-parser/

.PHONY: build start test stop clean rsync
