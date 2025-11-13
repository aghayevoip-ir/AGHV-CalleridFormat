#!/bin/bash

# AghayeVOIP CallerID Formatter - Simple Installer
echo "==================================="
echo "AghayeVOIP CallerID Formatter"
echo "==================================="
echo "Installing... Please wait..."

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

# Add to-cidformatter context
echo "3. Creating automatic formatter context..."
echo "" >> /etc/asterisk/extensions_custom.conf
echo "[to-cidformatter]" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,1,Set(IS_PSTN_CALL=1)" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,n,Set(SAVED_DID=\${EXTEN})" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,n,NoOp(Starting AghayeVOIP CallerID Formatter)" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,n,Gosub(AGHV_numberformatter,s,1)" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,n,NoOp(Formatter completed)" >> /etc/asterisk/extensions_custom.conf
echo "exten => _.,n,Goto(from-pstn,\${SAVED_DID},1)" >> /etc/asterisk/extensions_custom.conf

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