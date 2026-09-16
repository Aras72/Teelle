<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class ErrorPagesTest extends TestCase
{
    public function test_not_found_page_uses_the_teelle_error_design(): void
    {
        $this->get('/this-page-does-not-exist')->assertNotFound()
            ->assertSee('اینجا چیزی پیدا نکردیم')
            ->assertSee('برگشت به خانه');
    }

    public function test_all_operational_error_views_compile_with_human_copy(): void
    {
        foreach (['403', '404', '419', '429', '500', '503'] as $code) {
            $html = view("errors.{$code}")->render();
            $this->assertStringContainsString('error-card', $html);
            $this->assertStringContainsString('برگشت به خانه', $html);
        }
    }
}
