.PHONY: test test-all test-admin test-customer test-guest test-coverage test-parallel test-setup shell help

CONTAINER = php85
PROJECT_DIR = /var/www/html/toko-roti

define docker-exec
	docker exec -it $(CONTAINER) bash -c "cd $(PROJECT_DIR) && $(1)"
endef

test-setup:
	@echo "🔧 Setting up test environment..."
	@$(call docker-exec,php artisan migrate:fresh --seed)
	@echo "✅ Test environment ready!"

test test-all:
	@echo "🧪 Running all tests..."
	@$(call docker-exec,php artisan test)

test-admin:
	@echo "👨‍💼 Running admin tests..."
	@$(call docker-exec,php artisan test --filter=Admin)

test-customer:
	@echo "👤 Running customer tests..."
	@$(call docker-exec,php artisan test --filter=Customer)

test-guest:
	@echo "🌐 Running guest tests..."
	@$(call docker-exec,php artisan test tests/Feature/Customer/GuestCustomerTest.php)

test-coverage:
	@echo "📊 Running tests with coverage..."
	@$(call docker-exec,php artisan test --coverage)

test-parallel:
	@echo "⚡ Running tests in parallel..."
	@$(call docker-exec,php artisan test --parallel)

shell:
	@docker exec -it $(CONTAINER) bash -c "cd $(PROJECT_DIR) && bash"

help:
	@echo "Available commands:"
	@echo "  make test-setup    - Setup test environment"
	@echo "  make test          - Run all tests"
	@echo "  make test-admin    - Run admin tests"
	@echo "  make test-customer - Run customer tests"
	@echo "  make test-guest    - Run guest tests"
	@echo "  make test-coverage - Run with coverage"
	@echo "  make test-parallel - Run in parallel"
	@echo "  make shell         - Enter container shell"
