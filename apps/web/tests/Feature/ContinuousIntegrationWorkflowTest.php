<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContinuousIntegrationWorkflowTest extends TestCase
{
    public function test_quality_workflow_enforces_the_release_baseline(): void
    {
        $workflowPath = base_path('../../.github/workflows/quality.yml');
        $workflow = file_get_contents($workflowPath);

        $this->assertFileExists($workflowPath);
        $this->assertIsString($workflow);
        $this->assertStringContainsString('pull_request:', $workflow);
        $this->assertStringContainsString('workflow_dispatch:', $workflow);
        $this->assertStringContainsString('permissions:', $workflow);
        $this->assertStringContainsString('contents: read', $workflow);
        $this->assertStringContainsString('image: mysql:8.4', $workflow);
        $this->assertStringContainsString('php-version: "8.5"', $workflow);
        $this->assertStringContainsString('version: 11.22.0', $workflow);
        $this->assertStringContainsString('node-version: 22', $workflow);
        $this->assertStringContainsString('DB_SCHEMA_VALIDATION: "true"', $workflow);
        $this->assertStringContainsString('MYSQL_ROOT_HOST: "%"', $workflow);
        $this->assertStringContainsString('SET GLOBAL log_bin_trust_function_creators = 1', $workflow);
        $this->assertStringContainsString('composer install --no-interaction --prefer-dist --no-progress', $workflow);
        $this->assertStringContainsString('pnpm install --frozen-lockfile', $workflow);
        $this->assertStringContainsString('vendor/bin/phpunit', $workflow);
        $this->assertStringContainsString('vendor/bin/pint --test', $workflow);
        $this->assertStringContainsString('php artisan view:cache', $workflow);
        $this->assertStringContainsString('pnpm test', $workflow);
        $this->assertStringContainsString('pnpm build', $workflow);
        $this->assertTrue(strpos($workflow, 'pnpm build') < strpos($workflow, 'vendor/bin/phpunit'));
        $this->assertStringContainsString('composer audit --locked --no-interaction', $workflow);
        $this->assertStringContainsString('pnpm audit --prod --audit-level=low', $workflow);
        $this->assertStringNotContainsString('DB_PORT: 3500', $workflow);
    }
}
