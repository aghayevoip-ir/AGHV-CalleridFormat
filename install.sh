#!/bin/bash

# AghayeVOIP CallerID Formatter - Simple Automatic Installer
# Define colors
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

echo -e "${MAGENTA}###############################################################${NC}"
echo -e "${CYAN}    ___         __                   _    ______  ___ _____ ${NC}"
echo -e "${CYAN}   /   | ____ _/ /_  ____ ___  _____| |  / / __ \/  _/ __ \ ${NC}"
echo -e "${CYAN}  / /| |/ __ \`/ __ \/ __ \`/ / / / _ \ | / / / / // // /_/ ${NC}"
echo -e "${CYAN} / ___ / /_/ / / / / /_/ / /_/ /  __/ |/ / /_/ // // ____/  ${NC}"
echo -e "${CYAN}/_/  |_\__, /_/ /_/\__,_/\__, /\___/|___/\____/___/_/       ${NC}"
echo -e "${CYAN}      /____/            /____/                              ${NC}"
echo -e "${CYAN}                                                            ${NC}"
echo -e "${MAGENTA}###############################################################${NC}"
echo -e "${MAGENTA}                    https://aghayevoip.ir                      ${NC}"
echo -e "${MAGENTA}###############################################################${NC}"
echo ""
echo "Install AghayeVOIP CallerID Formatter - Final Version"
echo "aghayevoip.ir"
echo "AghayeVOIP Panel 1.0"
echo ""

# Check if running as root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root (use sudo)"
   exit 1
fi

# Copy formatter file
echo "1. Copying formatter configuration..."
cp extensions_aghayevoip_numberformatter.conf /etc/asterisk/
chown asterisk:asterisk /etc/asterisk/extensions_aghayevoip_numberformatter.conf
chmod 644 /etc/asterisk/extensions_aghayevoip_numberformatter.conf

# Add include to extensions_custom.conf
echo "2. Adding include to Asterisk configuration..."
echo "" >> /etc/asterisk/extensions_custom.conf
echo ";; AghayeVOIP CallerID Formatter" >> /etc/asterisk/extensions_custom.conf
echo "#include extensions_aghayevoip_numberformatter.conf" >> /etc/asterisk/extensions_custom.conf

# Add from-pstn-custom context (working solution)
echo "3. Creating automatic formatter context..."
echo "" >> /etc/asterisk/extensions_custom.conf
echo "[from-pstn-custom]" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,1,Gosub(AGHV_numberformatter,s,1)" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,n,Goto(from-did-direct-ivr,500,1)" >> /etc/asterisk/extensions_custom.conf

# Reload Asterisk
echo "4. Reloading Asterisk..."
asterisk -rx "dialplan reload"

echo "==================================="
echo "✅ Installation completed!"
echo "✅ Your trunk context remains unchanged"
echo "✅ All incoming calls will be automatically formatted"
echo "==================================="
echo ""
echo "To test: Make a call and check the CallerID format"
echo "For logs: tail -f /var/log/asterisk/full"