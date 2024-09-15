<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\Steps;
use App\Models\Step;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         \App\Models\User::create([
             'name' => 'Admin',
             'email' => 'admin@unibolsas.com.br',
             'password' => Hash::make('123456789'),
             'cargo' => 'MASTER',
         ]);
//    case CADASTRO = "Cadastro";
//    case FOTO = "Foto";
//    case PAGAMENTO = "Pagamento";
//    case DOWNLOAD = "Download";

         Step::query()->firstOrCreate([
             "name" => Steps::CADASTRO->value,
         ],[
             "name" => Steps::CADASTRO->value,
             "redirect_uri" => "/"
         ]);
        Step::query()->firstOrCreate([
            "name" => Steps::FOTO->value,
        ],[
            "name" => Steps::FOTO->value,
            "redirect_uri" => "/student-id/create/upload-photo"
        ]);
        Step::query()->firstOrCreate([
            "name" => Steps::PAGAMENTO->value,
        ],[
            "name" => Steps::PAGAMENTO->value,
            "redirect_uri" => "/student-id/create/checkout"
        ]);
        Step::query()->firstOrCreate([
            "name" => Steps::AGUARDANDOPAGAMENTO->value,
        ],[
            "name" => Steps::AGUARDANDOPAGAMENTO->value,
            "redirect_uri" => "/student-id/payment/pix"
        ]);
        Step::query()->firstOrCreate([
            "name" => Steps::DOWNLOAD->value,
        ],[
            "name" => Steps::DOWNLOAD->value,
            "redirect_uri" => "/student-id/gerar-carteira"
        ]);
    }
}
