FROM redis/redis-stack-server:latest

EXPOSE 6379

# Start Redis Stack server with the RediSearch module
CMD ["redis-stack-server", "--requirepass", "secret"]
