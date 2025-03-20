CREATE TABLE companies (
 id SERIAL PRIMARY KEY,
 name VARCHAR(255),
 website VARCHAR(255),
 address TEXT,
 source VARCHAR(50), -- Es: 'API_1', 'SCRAPER_2', 'MANUAL'
 inserted_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE normalized_companies (
 id SERIAL PRIMARY KEY,
 name VARCHAR(255) UNIQUE,
 canonical_website VARCHAR(255),
 address TEXT
);


--populating companies table


INSERT INTO companies (name, website, address, source, inserted_at) VALUES
('OpenAI', 'https://openai.com', 'San Francisco, CA', 'API_1', NOW()),
('Innovatiespotter', 'https://innovatiespotter.com', 'Groningen', 'SCRAPER_2', NOW()),
('Microsoft', 'https://microsoft.com', 'Redmond, WA', 'API_1', NOW()),
('Google', 'https://google.com', 'Mountain View, CA', 'SCRAPER_2', NOW()),
('Amazon', 'https://amazon.com', 'Seattle, WA', 'MANUAL', NOW()),
('Facebook', 'https://facebook.com', 'Menlo Park, CA', 'API_1', NOW()),
('Twitter', 'https://twitter.com', 'San Francisco, CA', 'SCRAPER_2', NOW()),
('Netflix', 'https://netflix.com', 'Los Gatos, CA', 'API_1', NOW()),
('YouTube', 'https://youtube.com', 'San Bruno, CA', 'SCRAPER_2', NOW()),
('LinkedIn', 'https://linkedin.com', 'Sunnyvale, CA', 'MANUAL', NOW()),
('Tesla', 'https://tesla.com', 'Austin, TX', 'SCRAPER_2', NOW()),
('Uber', 'https://uber.com', 'San Francisco, CA', 'API_1', NOW()),
('Spotify', 'https://spotify.com', 'Stockholm, Sweden', 'MANUAL', NOW()),
('Airbnb', 'https://airbnb.com', 'San Francisco, CA', 'SCRAPER_2', NOW()),
('Snapchat', 'https://snapchat.com', 'Venice, LA', 'API_1', NOW()),
('Slack', 'https://slack.com', 'San Francisco, CA', 'API_1', NOW()),
('Zoom', 'https://zoom.us', 'San Jose, CA', 'SCRAPER_2', NOW()),
('Dropbox', 'https://dropbox.com', 'San Francisco, CA', 'API_1', NOW()),
('Salesforce', 'https://salesforce.com', 'San Francisco, CA', 'SCRAPER_2', NOW()),
('Reddit', 'https://reddit.com', 'San Francisco, CA', 'API_1', NOW()),
('TikTok', 'https://tiktok.com', 'Beijing, China', 'SCRAPER_2', NOW()),
('Pinterest', 'https://pinterest.com', 'San Francisco, CA', 'MANUAL', NOW()),
('Oracle', 'https://oracle.com', 'Austin, TX', 'API_1', NOW()),
('Intel', 'https://intel.com', 'Santa Clara, CA', 'SCRAPER_2', NOW()),
('IBM', 'https://ibm.com', 'Armonk, NY', 'MANUAL', NOW());

INSERT INTO companies (name, website, address, source, inserted_at) VALUES
('OpenAI', 'https://openai.com', 'San Francisco, CA', 'SCRAPER_2', NOW()),
('Innovatiespotter', 'https://innovatiespotter.com', 'Groningen', 'API_1', NOW()),
('Microsoft', 'https://microsoft.com', 'Redmond, WA', 'SCRAPER_2', NOW()),
('Google', 'https://google.com', 'Mountain View, CA', 'SCRAPER_2', NOW());

INSERT INTO companies (name, website, address, source, inserted_at) VALUES
('Openai', 'https://openai.com', 'San Francisco, CA', 'API_1', NOW()),
('InnovatieSpotter', 'https://innovatiespotter.com', 'Groningen', 'SCRAPER_2', NOW());

--1. Identify potential duplicates

select
	lower(name) as normalized_name,
	count(*) as duplicates_count,
	string_agg(distinct source, ', ') as sources
from 
	companies 
group by
	 normalized_name
having
	count(*) >1;


--2. Normalize the data

insert into normalized_companies (name, canonical_website, address)
with RankedCompanies as (
    select 
        lower(name) as normalized_name,
        website,
        address,
        case 
            when source = 'MANUAL' then 1
            when source = 'API_1' then 2
            when source = 'SCRAPER_2' then 3
        end as source_rank,
        row_number() over (partition by lower(name) order by
            case
                when source = 'MANUAL' then 1
                when source = 'API_1' then 2
                when source = 'SCRAPER_2' then 3
            end) as rn
    from companies
)
select 
    normalized_name,
    website,
    address
from RankedCompanies
where rn = 1; 

select * from normalized_companies

--3. Get statistics on sources

select
	source,
	count(*)
from
	companies
group by
	source
order by 
	count desc;



