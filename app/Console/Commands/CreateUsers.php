<?php

namespace App\Console\Commands;

use Faker\Generator;
use Illuminate\Console\Command;

class CreateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:users {users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un certo numero di utenti';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param Generator $faker
     * @return mixed
     */
    public function handle(Generator $faker)
    {
        $this->info('Stiamo creando degli utenti');
        if ($this->argument('users') > 0) {
            for ($i = 1; $i <= $this->argument('users'); $i++) {

                $users[] =
                    ['name' => $faker->name,
                        'email' => $faker->unique()->safeEmail,
                        'password' => bcrypt('secret'),
                        'remember_token' => str_random(10)];
            }

            $progressBar = $this->output->createProgressBar(count($users));
            if ($this->confirm('Vuoi davvero creare gli utenti?(yes/no:default=no)')) {
                foreach ($users as $user) {
                    $this->info("\nCreating user " . $user['name']);
                    \App\User::create($user);
                    $progressBar->advance();
                }

                $progressBar->finish();
                $this->info("\nOperation completed!");
            }
        }else{
            $this->info('Nessun utente creato');
        }
    }

}
