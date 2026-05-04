#!/bin/bash

CONTAINER="php85"
PROJECT_DIR="/var/www/html/toko-roti"

echo "🧪 Running Tests in Docker Container: $CONTAINER"
echo "================================================"

run_in_container() {
    docker exec -it $CONTAINER bash -c "cd $PROJECT_DIR && $1"
}

if ! docker ps | grep -q $CONTAINER; then
    echo "❌ Container $CONTAINER is not running!"
    exit 1
fi

case "$1" in
    "all")
        echo "📦 Running all tests..."
        run_in_container "php artisan test"
        ;;
    "admin")
        echo "👨‍💼 Running admin tests..."
        run_in_container "php artisan test --filter=Admin"
        ;;
    "customer")
        echo "👤 Running customer tests..."
        run_in_container "php artisan test --filter=Customer"
        ;;
    "guest")
        echo "🌐 Running guest tests..."
        run_in_container "php artisan test tests/Feature/Customer/GuestCustomerTest.php"
        ;;
    "coverage")
        echo "📊 Running tests with coverage..."
        run_in_container "php artisan test --coverage"
        ;;
    "parallel")
        echo "⚡ Running tests in parallel..."
        run_in_container "php artisan test --parallel"
        ;;
    "setup")
        echo "🔧 Setting up test environment..."
        run_in_container "php artisan migrate:fresh --seed"
        echo "✅ Test environment ready!"
        ;;
    *)
        echo "Usage: ./run-tests.sh [command]"
        echo ""
        echo "Commands:"
        echo "  all       - Run all tests"
        echo "  admin     - Run admin panel tests"
        echo "  customer  - Run customer tests"
        echo "  guest     - Run guest tests"
        echo "  coverage  - Run with coverage report"
        echo "  parallel  - Run tests in parallel"
        echo "  setup     - Setup test environment"
        ;;
esac
