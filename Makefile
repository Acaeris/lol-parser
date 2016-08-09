build:
	docker build -t lol-parser -f Dockerfile .

start: stop
	# Queue
	docker run -d --name lol-queue rabbitmq:latest

	# Database
	docker run -d --name lol-db -e "MYSQL_ROOT_PASSWORD=4wV*4ynnCMXj" mysql:latest

	# Parser
	docker run -d --name lol-parser \
		--link lol-queue:rmq.local \
		--link lol-db:mysql.local \
		lol-parser

stop:
	@docker rm -vf lol-parser lol-queue lol-db||:

clean: stop
	docker rmi -f lol-parser rabbitmq mysql

.PHONY: build start stop clean