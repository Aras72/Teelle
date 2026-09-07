<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('title');
            $table->unsignedTinyInteger('duration_months');
            $table->unsignedBigInteger('price_minor')->nullable();
            $table->char('currency', 3)->default('IRR');
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
            $table->unique('duration_months');
        });

        Schema::create('purchases', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->enum('status', ['pending', 'paid', 'payment_failed', 'cancelled', 'refunded'])->default('pending')->index();
            $table->unsignedBigInteger('amount_minor');
            $table->char('currency', 3)->default('IRR');
            $table->string('provider', 64);
            $table->string('provider_reference', 128)->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['provider', 'provider_reference']);
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('payment_events', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('purchase_id')->constrained()->restrictOnDelete();
            $table->string('provider', 64);
            $table->string('provider_event_id', 128);
            $table->string('event_type', 64);
            $table->json('normalized_payload_json')->nullable();
            $table->char('payload_checksum', 64);
            $table->timestamp('occurred_at')->nullable();
            $table->timestamp('recorded_at')->useCurrent();
            $table->unique(['provider', 'provider_event_id']);
            $table->index(['purchase_id', 'recorded_at']);
        });

        Schema::create('entitlements', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('purchase_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('product_code', 32)->default('jigari');
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled', 'refunded', 'revoked'])->default('pending')->index();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamp('revoked_at')->nullable();
            $table->string('revocation_reason')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'product_code', 'status', 'starts_at', 'ends_at'], 'entitlement_active_lookup');
        });

        Schema::create('outbox_messages', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('aggregate_type', 96);
            $table->string('aggregate_id', 64);
            $table->string('message_type', 96)->index();
            $table->json('payload_json');
            $table->unsignedSmallInteger('attempts')->default(0);
            $table->timestamp('available_at')->index();
            $table->timestamp('processed_at')->nullable()->index();
            $table->text('last_error')->nullable();
            $table->timestamps();
            $table->index(['aggregate_type', 'aggregate_id']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE plans ADD CONSTRAINT plans_allowed_duration CHECK (duration_months IN (3, 6, 12))');
            DB::statement('ALTER TABLE entitlements ADD CONSTRAINT entitlements_valid_period CHECK (ends_at > starts_at)');
            DB::unprepared("CREATE TRIGGER payment_events_block_update BEFORE UPDATE ON payment_events FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'payment_events are append-only'");
            DB::unprepared("CREATE TRIGGER payment_events_block_delete BEFORE DELETE ON payment_events FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'payment_events are append-only'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS payment_events_block_update');
            DB::unprepared('DROP TRIGGER IF EXISTS payment_events_block_delete');
        }

        Schema::dropIfExists('outbox_messages');
        Schema::dropIfExists('entitlements');
        Schema::dropIfExists('payment_events');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('plans');
    }
};
