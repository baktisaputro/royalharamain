-- ============================================================
-- Royal Haramain — Supabase Schema
-- Jalankan di Supabase Dashboard → SQL Editor → New Query → Run
-- Project: https://yxdpovmnkxpqdudgwtaz.supabase.co
-- ============================================================

-- 1. PACKAGES
create table if not exists packages (
  id uuid primary key default gen_random_uuid(),
  title text not null,
  price text not null,
  duration text,
  badge text,
  facilities text[] default '{}',
  image_url text,
  url text default '#',
  featured boolean default false,
  created_at timestamp with time zone default now()
);

-- 2. ARTICLES
create table if not exists articles (
  id uuid primary key default gen_random_uuid(),
  title text not null,
  excerpt text not null,
  date text,
  image_url text,
  url text default '#',
  created_at timestamp with time zone default now()
);

-- 3. LEADS (pendaftar)
create table if not exists leads (
  id uuid primary key default gen_random_uuid(),
  name text not null,
  phone text not null,
  interest text,
  message text,
  created_at timestamp with time zone default now()
);

-- 4. PROMOS (popup)
create table if not exists promos (
  id int primary key, -- selalu 1
  enabled boolean default false,
  badge text,
  title text,
  message text,
  image_url text,
  link text,
  delay int default 3,
  show_once boolean default true,
  updated_at timestamp with time zone default now()
);
insert into promos (id, enabled, badge, title, message, image_url, link, delay, show_once)
values (1, false, 'PROMO SPESIAL', 'Diskon 10% Umroh Reguler', 'Daftar sebelum tanggal 30 dan dapatkan diskon spesial. Kuota terbatas!', 'assets/images/logo.png', '#daftar', 3, true)
on conflict (id) do nothing;

-- 5. ENABLE RLS
alter table packages enable row level security;
alter table articles enable row level security;
alter table leads enable row level security;
alter table promos enable row level security;

-- 6. POLICIES: public bisa read, hanya authenticated bisa write
drop policy if exists "public read packages" on packages;
create policy "public read packages" on packages for select using (true);
drop policy if exists "auth write packages" on packages;
create policy "auth write packages" on packages for all using (auth.role() = 'authenticated') with check (auth.role() = 'authenticated');

drop policy if exists "public read articles" on articles;
create policy "public read articles" on articles for select using (true);
drop policy if exists "auth write articles" on articles;
create policy "auth write articles" on articles for all using (auth.role() = 'authenticated') with check (auth.role() = 'authenticated');

drop policy if exists "public insert leads" on leads;
create policy "public insert leads" on leads for insert with check (true);
drop policy if exists "auth read leads" on leads;
create policy "auth read leads" on leads for select using (auth.role() = 'authenticated');
drop policy if exists "auth delete leads" on leads;
create policy "auth delete leads" on leads for delete using (auth.role() = 'authenticated');

drop policy if exists "public read promos" on promos;
create policy "public read promos" on promos for select using (true);
drop policy if exists "auth write promos" on promos;
create policy "auth write promos" on promos for all using (auth.role() = 'authenticated') with check (auth.role() = 'authenticated');

-- 7. STORAGE BUCKET untuk gambar (jalankan via Dashboard > Storage jika belum)
-- Buat bucket 'royalharamain' public, lalu jalankan:
-- insert into storage.buckets (id, name, public) values ('royalharamain', 'royalharamain', true) on conflict (id) do nothing;
-- Policy storage: public read, auth write (atur di Dashboard > Storage > Policies)

-- 8. SEED DATA (opsional — pindahkan dari js/data.js)
-- Jalankan manual setelah RLS, atau biarkan admin input via admin.html
