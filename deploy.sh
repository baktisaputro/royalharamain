#!/bin/bash
# =============================================================
# Royal Haramain - Deploy Script (untuk cPanel Terminal)
# Cara pakai:
#   1. Buka cPanel → Terminal
#   2. cd public_html/royalharamain.com
#   3. chmod +x deploy.sh
#   4. ./deploy.sh
# =============================================================

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}=== Royal Haramain - Deploy ===${NC}"
echo ""

# Cek apakah di folder yang benar
if [ ! -f "index.php" ]; then
    echo -e "${RED}Error: index.php tidak ditemukan.${NC}"
    echo "Pastikan Anda di folder: public_html/royalharamain.com"
    exit 1
fi

# Cek apakah git sudah ter-setup
if [ ! -d ".git" ]; then
    echo -e "${YELLOW}Git belum ter-setup. Menjalankan setup pertama kali...${NC}"
    echo ""
    git init
    git remote add origin https://github.com/baktisaputro/royalharamain.git
    git fetch origin master
    
    # Backup config.php jika ada
    if [ -f "app/config.php" ]; then
        cp app/config.php app/config.php.bak
        echo -e "${GREEN}✓ Config.php di-backup${NC}"
    fi
    
    git checkout -f master
    
    # Restore config.php
    if [ -f "app/config.php.bak" ]; then
        cp app/config.php.bak app/config.php
        echo -e "${GREEN}✓ Config.php di-restore${NC}"
    fi
    
    # Protect config.php
    git update-index --skip-worktree app/config.php
    
    echo -e "${GREEN}✓ Setup selesai!${NC}"
else
    # Config.php sudah terprotect
    git update-index --skip-worktree app/config.php 2>/dev/null || true
    
    # Pull dari GitHub
    echo -e "${YELLOW}Pulling dari GitHub...${NC}"
    git pull origin master
fi

echo ""
echo -e "${GREEN}=== Deploy selesai! ===${NC}"
echo -e "Cek website: https://royalharamain.com"
echo -e "Cek admin:   https://royalharamain.com/admin/login.php"
echo ""
