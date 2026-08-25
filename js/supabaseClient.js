/* ============================================================
   Supabase Client — Royal Haramain
   Project: https://yxdpovmnkxpqdudgwtaz.supabase.co
   Anon key: public, aman untuk client-side (RLS tetap jaga)
   ============================================================ */
const SUPABASE_URL = "https://yxdpovmnkxpqdudgwtaz.supabase.co";
const SUPABASE_ANON_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Inl4ZHBvdm1ua3hwcWR1ZGd3dGF6Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODc2NTYwMDYsImV4cCI6MjEwMzIzMjAwNn0.BETR3_VsgRVQm6BxAl9-5BUk0liGGTELqntpnLnQ0ng";

// supabase-js UMD memuat global `supabase`
const supabaseClient = (typeof supabase !== "undefined")
  ? supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY)
  : null;

if (!supabaseClient) {
  console.warn("[supabase] supabase-js belum dimuat. Pastikan CDN dimuat sebelum file ini.");
}
