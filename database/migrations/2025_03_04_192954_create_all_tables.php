<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    
        public function up() {
            Schema::create('Organization', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('nom');
                $table->string('contact')->nullable();
                $table->string('adresse_complete')->nullable();
                $table->uuid('created_by')->nullable();
                $table->uuid('updated_by')->nullable();
                $table->uuid('deleted_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
    
            Schema::create('users', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('nom');
                $table->string('email', 191)->unique();
                $table->string('mot_de_passe');
                $table->decimal('solde', 10, 2)->default(0.00);
                $table->uuid('organism_id');
                $table->json('preferences')->nullable();
                $table->uuid('created_by')->nullable();
                $table->uuid('updated_by')->nullable();
                $table->uuid('deleted_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
    
            Schema::create('transactions', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('utilisateur_id');
                $table->decimal('montant', 10, 2);
                $table->uuid('transaction_type_id');
                $table->uuid('created_by')->nullable();
                $table->uuid('updated_by')->nullable();
                $table->uuid('deleted_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
    
            Schema::create('restaurants', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('nom');
                $table->string('adresse')->nullable();
                $table->string('contact')->nullable();
                $table->uuid('created_by')->nullable();
                $table->uuid('updated_by')->nullable();
                $table->uuid('deleted_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
    
            Schema::create('plats', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('nom');
                $table->text('description')->nullable();
                $table->decimal('prix', 10, 2);
                $table->uuid('restaurant_id')->constrained('restaurants')->onDelete('cascade');
                $table->timestamps();
            });
    
            Schema::create('options_plat', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('plat_id')->constrained('plats')->onDelete('cascade');
                $table->string('nom');
                $table->decimal('prix_supplementaire', 10, 2)->default(0.00);
                $table->timestamps();
            });
    
            Schema::create('commandes', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
                $table->uuid('statut_id')->constrained('statuts_commandes');
                $table->timestamps();
            });
    
            Schema::create('commande_contenu', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('commande_id')->constrained('commandes')->onDelete('cascade');
                $table->uuid('plat_id')->constrained('plats')->onDelete('cascade');
                $table->integer('quantite')->default(1);
                $table->timestamps();
            });
    
            Schema::create('evaluations', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
                $table->uuid('restaurant_id')->nullable()->constrained('restaurants')->onDelete('cascade');
                $table->uuid('plat_id')->nullable()->constrained('plats')->onDelete('cascade');
                $table->integer('note');
                $table->text('commentaire')->nullable();
                $table->timestamps();
            });
    
            Schema::create('favoris', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
                $table->uuid('plat_id')->constrained('plats')->onDelete('cascade');
                $table->timestamps();
            });
    
            Schema::create('statuts_commandes', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('nom');
                $table->timestamps();
            });
    
            Schema::create('types_transactions', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('nom');
                $table->timestamps();
            });
    
            Schema::create('jours', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('nom');
                $table->timestamps();
            });
        }
    
        public function down() {
            Schema::dropIfExists('favoris');
            Schema::dropIfExists('evaluations');
            Schema::dropIfExists('commande_contenu');
            Schema::dropIfExists('commandes');
            Schema::dropIfExists('options_plat');
            Schema::dropIfExists('plats');
            Schema::dropIfExists('types_transactions');
            Schema::dropIfExists('statuts_commandes');
            Schema::dropIfExists('restaurants');
            Schema::dropIfExists('transactions');
            Schema::dropIfExists('utilisateurs');
            Schema::dropIfExists('organism');
            Schema::dropIfExists('jours');
        }
};
