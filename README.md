# Issabel/Freepbx AghayeVOIP CalleridFormatter
Translation:

Caller number correction, suitable for local landlines in Iran: Sometimes, incoming numbers are missing the initial "0" or include extra numbers and symbols like "98." With this script, the incoming caller ID is corrected as it enters your telephony system. This script is crucial for reporting modules and applications.

اصلاح شماره تماس گیرنده، مناسب برای خطوط شهری داخلی ایران، برخی مواقع شماره های ورودی بدون 0 اولیه یا همراه با عدد ها وعلائم اضافی مثل 98 است، با این اسکریپت در ورودی سیستم تلفنی شما کالر آی دی ورودی اصلاح می شود. این اسکریپ برای ماژول ها و برنامه های گزارشگیری بسیار حیاتی است.
.
## Instalation (راهنمای نصب)

#1. run on your Linux CLI.

## 1. دستور زیر را بر روی کنسول لینوکس ایزابل خود اجرا کنید.
```bash
# Simple automatic installation (updated method):
git clone https://github.com/aghayevoip-ir/AGHV-CalleridFormat.git
cd AGHV-CalleridFormat
sudo bash install.sh
```


#2. Automatic Installation - No Trunk Context Change Required!

## 2. نصب خودکار - بدون نیاز به تغییر کانتکس ترانک!

The installer automatically adds the formatter to your Asterisk system without requiring any manual trunk configuration changes.

نصب کننده به صورت خودکار فرمت کننده را به سیستم استریسک اضافه می کند بدون نیاز به تغییر دستی تنظیمات ترانک.

### How it works (نحوه کار):
The installer automatically:
1. Copies the formatter configuration to `/etc/asterisk/extensions_aghayevoip_numberformatter.conf`
2. Includes it in `extensions_custom.conf`
3. Creates a `from-pstn-custom` context that processes calls before routing
4. Automatically formats CallerID for all incoming calls
5. Your trunk context remains unchanged - calls are automatically processed!

نصب کننده به صورت خودکار:
1. فایل پیکربندی فرمت کننده را به `/etc/asterisk/extensions_aghayevoip_numberformatter.conf` کپی می‌کند
2. آن را در `extensions_custom.conf` قرار می‌دهد
3. یک کانتکس `from-pstn-custom` ایجاد می‌کند که تماس‌ها را قبل از مسیریابی پردازش می‌کند
4. کالر آیدی تماس‌های ورودی را به صورت خودکار فرمت می‌کند
5. کانتکس ترانک شما بدون تغییر باقی می‌ماند - تماس‌ها به صورت خودکار پردازش می‌شوند!

## Give a Star! ⭐ یک ستاره با ما بدهید
If you like this AghayeVOIP project or plan to use it in the future, please give it a star. Thanks 🙏
