<?php

use Illuminate\Database\Seeder;
use OpenDominion\Factories\DominionFactory;
use OpenDominion\Models\Dominion;
use OpenDominion\Models\Race;
use OpenDominion\Models\Round;
use OpenDominion\Models\RoundLeague;
use OpenDominion\Models\User;

class DevelopmentSeeder extends Seeder
{
    /** @var DominionFactory */
    protected $dominionFactory;

    /** @var string */
    protected $userPassword = 'test';

    /**
     * DevelopmentSeeder constructor.
     *
     * @param DominionFactory $dominionFactory
     */
    public function __construct(DominionFactory $dominionFactory)
    {
        $this->dominionFactory = $dominionFactory;
    }

    public function run()
    {
        $user = $this->createUser();
        $round = $this->createRound();
        $this->createRealmAndDominion($user, $round);

        $this->command->info(<<<INFO

Done seeding data.
A round, user and dominion have been created for your convenience.
You may login with email '{$user->email}' and password '{$this->userPassword}'.

INFO
        );
    }

    protected function createUser(): User
    {
        $this->command->info('Creating test user');

        $user = User::create([
            'email' => 'email@example.com',
            'password' => bcrypt($this->userPassword),
            'display_name' => 'Dev User',
            'activated' => true,
            'activation_code' => str_random(),
        ]);

        $user->assignRole(['Developer', 'Administrator', 'Moderator']);

        return $user;
    }

    protected function createRound(): Round
    {
        $this->command->info('Creating development round');

        return Round::create([
            'round_league_id' => RoundLeague::where('key', 'standard')->firstOrFail()->id,
            'number' => 1,
            'name' => 'Dev Round',
            'start_date' => new DateTime('today midnight'),
            'end_date' => new DateTime('+50 days midnight'),
        ]);
    }

    protected function createRealmAndDominion(User $user, Round $round): Dominion
    {
        $this->command->info('Creating realm and dominion');

        return $this->dominionFactory->create(
            $user,
            $round,
            Race::where('name', 'Human')->firstOrFail(),
            'random',
            'Dev Dominion'
        );
    }
}
