<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\people; // Make sure to import your model

class podcast_people extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $peoples = [
            [
                'name' => 'Joe Rogan',
                'DOB' => '1967-08-11',
                'rss' => 'https://joeroganpodcast.com/rss',
                'interests' => 'Comedy, MMA, Philosophy, Current Events'
            ],
            [
                'name' => 'Marc Maron',
                'DOB' => '1963-09-27',
                'rss' => 'https://wtfpod.libsyn.com/rss',
                'interests' => 'Comedy, Interviews, Pop Culture'
            ],
            [
                'name' => 'Tim Ferriss',
                'DOB' => '1977-07-20',
                'rss' => 'https://tim.blog/feed/podcast',
                'interests' => 'Productivity, Health, Business, Life Optimization'
            ],
            [
                'name' => 'Sam Harris',
                'DOB' => '1967-04-09',
                'rss' => 'https://samharris.org/rss',
                'interests' => 'Neuroscience, Philosophy, Politics, Mindfulness'
            ],
            [
                'name' => 'Lex Fridman',
                'DOB' => '1986-08-15',
                'rss' => 'https://lexfridman.com/feed/podcast',
                'interests' => 'Artificial Intelligence, Science, Technology, Philosophy'
            ],
            [
                'name' => 'Dax Shepard',
                'DOB' => '1975-01-02',
                'rss' => 'https://armchairexpertpod.com/rss',
                'interests' => 'Psychology, Relationships, Pop Culture, Personal Growth'
            ],
            [
                'name' => 'Rich Roll',
                'DOB' => '1966-10-20',
                'rss' => 'https://www.richroll.com/feed/podcast',
                'interests' => 'Health, Wellness, Fitness, Nutrition'
            ],
            [
                'name' => 'Conan O’Brien',
                'DOB' => '1963-04-18',
                'rss' => 'https://conanobrienneedsafriend.libsyn.com/rss',
                'interests' => 'Comedy, Interviews, Storytelling, Entertainment'
            ],
            [
                'name' => 'Russell Brand',
                'DOB' => '1975-06-04',
                'rss' => 'https://russellbrand.com/rss',
                'interests' => 'Politics, Comedy, Spirituality, Philosophy'
            ],
            [
                'name' => 'Jordan Harbinger',
                'DOB' => '1980-02-19',
                'rss' => 'https://www.jordanharbinger.com/podcast/feed',
                'interests' => 'Social Dynamics, Interviews, Networking, Personal Development'
            ],
            [
                'name' => 'Brené Brown',
                'DOB' => '1965-11-18',
                'rss' => 'https://brenebrown.com/rss',
                'interests' => 'Vulnerability, Leadership, Personal Growth'
            ],
            [
                'name' => 'Neil deGrasse Tyson',
                'DOB' => '1958-10-05',
                'rss' => 'https://www.startalkradio.net/feed',
                'interests' => 'Science, Space, Astrophysics'
            ],
            [
                'name' => 'Kara Swisher',
                'DOB' => '1962-12-11',
                'rss' => 'https://www.vox.com/recode/feed',
                'interests' => 'Technology, Business, Media'
            ],
            [
                'name' => 'Esther Perel',
                'DOB' => '1958-05-10',
                'rss' => 'https://whereshouldwebegin.estherperel.com/rss',
                'interests' => 'Relationships, Therapy, Love'
            ],
            [
                'name' => 'Guy Raz',
                'DOB' => '1975-02-09',
                'rss' => 'https://howibuiltthis.npr.org/rss',
                'interests' => 'Business, Entrepreneurship, Storytelling'
            ],
            [
                'name' => 'Dan Carlin',
                'DOB' => '1965-11-14',
                'rss' => 'https://dchh.libsyn.com/rss',
                'interests' => 'History, Politics, War'
            ],
            [
                'name' => 'Malcolm Gladwell',
                'DOB' => '1963-09-03',
                'rss' => 'https://revisionisthistory.com/rss',
                'interests' => 'History, Psychology, Society'
            ],
            [
                'name' => 'Jocko Willink',
                'DOB' => '1971-09-08',
                'rss' => 'https://jockopodcast.com/rss',
                'interests' => 'Leadership, Military, Discipline, Personal Development'
            ],
            [
                'name' => 'Tony Robbins',
                'DOB' => '1960-02-29',
                'rss' => 'https://tonyrobbins.com/feed/podcast',
                'interests' => 'Motivation, Success, Leadership, Personal Development'
            ],
            [
                'name' => 'Seth Godin',
                'DOB' => '1960-07-10',
                'rss' => 'https://seths.blog/feed',
                'interests' => 'Marketing, Creativity, Entrepreneurship, Leadership'
            ],
            [
                'name' => 'Gary Vaynerchuk',
                'DOB' => '1975-11-14',
                'rss' => 'https://www.garyvaynerchuk.com/feed/podcast',
                'interests' => 'Entrepreneurship, Marketing, Personal Development'
            ],
            [
                'name' => 'Andrew Huberman',
                'DOB' => '1975-09-26',
                'rss' => 'https://hubermanlab.com/feed',
                'interests' => 'Neuroscience, Health, Wellness, Performance'
            ],
            [
                'name' => 'Elizabeth Gilbert',
                'DOB' => '1969-07-18',
                'rss' => 'https://magiclessons.libsyn.com/rss',
                'interests' => 'Creativity, Writing, Personal Growth'
            ],
            [
                'name' => 'Oprah Winfrey',
                'DOB' => '1954-01-29',
                'rss' => 'https://oprah.com/rss',
                'interests' => 'Self-Improvement, Interviews, Motivation'
            ],
            [
                'name' => 'Nikki Glaser',
                'DOB' => '1984-06-01',
                'rss' => 'https://youupsiriusxm.libsyn.com/rss',
                'interests' => 'Comedy, Relationships, Sex, Culture'
            ],
            [
                'name' => 'Rainn Wilson',
                'DOB' => '1966-01-20',
                'rss' => 'https://soulpancake.com/rss',
                'interests' => 'Spirituality, Comedy, Social Change'
            ],
            [
                'name' => 'Pete Holmes',
                'DOB' => '1979-03-30',
                'rss' => 'https://youmadeitweird.libsyn.com/rss',
                'interests' => 'Comedy, Spirituality, Relationships'
            ],
            [
                'name' => 'Aubrey Marcus',
                'DOB' => '1981-02-28',
                'rss' => 'https://www.aubreymarcus.com/feed/podcast',
                'interests' => 'Health, Philosophy, Psychedelics, Performance'
            ],
            [
                'name' => 'Rachel Maddow',
                'DOB' => '1973-04-01',
                'rss' => 'https://www.msnbc.com/rss',
                'interests' => 'Politics, History, News'
            ],
            [
                'name' => 'Sean Carroll',
                'DOB' => '1966-10-05',
                'rss' => 'https://www.preposterousuniverse.com/podcast/feed',
                'interests' => 'Physics, Philosophy, Science'
            ],
            [
                'name' => 'Ezra Klein',
                'DOB' => '1984-05-09',
                'rss' => 'https://www.vox.com/ezra-klein-show/rss',
                'interests' => 'Politics, Society, Culture'
            ],
            [
                'name' => 'Brian Cox',
                'DOB' => '1968-03-03',
                'rss' => 'https://www.bbc.co.uk/podcasts/feed',
                'interests' => 'Physics, Science, Universe'
            ],
            [
                'name' => 'Anna Faris',
                'DOB' => '1976-11-29',
                'rss' => 'https://unqualified.libsyn.com/rss',
                'interests' => 'Comedy, Relationships, Advice'
            ],
            [
                'name' => 'Adam Grant',
                'DOB' => '1981-08-13',
                'rss' => 'https://worklife.ted.com/feed',
                'interests' => 'Psychology, Work, Leadership, Personal Development'
            ],
            [
                'name' => 'Tom Bilyeu',
                'DOB' => '1976-03-30',
                'rss' => 'https://impacttheory.libsyn.com/rss',
                'interests' => 'Entrepreneurship, Motivation, Personal Development'
            ],
            [
                'name' => 'Mike Tyson',
                'DOB' => '1966-06-30',
                'rss' => 'https://hotboxinpodcast.com/feed',
                'interests' => 'Boxing, Motivation, Personal Growth'
            ],
            [
                'name' => 'Gretchen Rubin',
                'DOB' => '1965-12-14',
                'rss' => 'https://gretchenrubin.com/feed',
                'interests' => 'Happiness, Habits, Personal Development'
            ],
            [
                'name' => 'Jon Favreau',
                'DOB' => '1981-06-02',
                'rss' => 'https://crooked.com/feed',
                'interests' => 'Politics, Culture, Society'
            ],
            [
                'name' => 'Ali Abdaal',
                'DOB' => '1994-05-11',
                'rss' => 'https://aliabdaal.com/feed/podcast',
                'interests' => 'Productivity, Personal Development, Creativity'
            ],
            [
                'name' => 'Whitney Cummings',
                'DOB' => '1982-09-04',
                'rss' => 'https://goodforyoupodcast.libsyn.com/rss',
                'interests' => 'Comedy, Relationships, Culture'
            ],
            [
                'name' => 'Patrick Bet-David',
                'DOB' => '1978-10-18',
                'rss' => 'https://valuetainment.com/feed',
                'interests' => 'Business, Entrepreneurship, Leadership'
            ],
            
        ];

        foreach ($peoples as $people) {
            people::create($people); // Create a new WowClass entry
        }
        
    }
}
