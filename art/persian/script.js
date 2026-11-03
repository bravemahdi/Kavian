console.log('✅ script.js loaded successfully! (Persian version)');

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

function showProfile(id) {
    localStorage.setItem('selectedFigure', id);
    window.location.href = 'profile.php';
}

const figures = [
    {
        id: 1,
        name: "حافظ",
        lastName: "شیرازی",
        fullName: "خواجه شمس‌الدین محمد حافظ شیرازی",
        birth: "۷۲۶ ه.ق",
        birthPlace: "شیراز",
        death: "۷۹۲ ه.ق",
        deathPlace: "شیراز",
        job: "شاعر",
        era: "اسلامی",
        categories: ["شاعر", "عرفان", "ادبیات فارسی", "حکمت"],
        quote: "بیا تا تال براق‌شانیم و بر ساغر اندازیم\nفلک سقف بشکانییم و زنگ‌های در دراندازیم",
        shortStory: "خواجه شمس‌الدین محمد حافظ شیرازی در سال ۷۲۶ هجری قمری در شیراز متولد شد. از کودکی به قرآن و علوم دینی علاقه داشت و به همین دلیل به 'حافظ' معروف شد. غزل‌های نابش او را به شهرت رساند و دیوانش بعد از قرآن بیشترین فال را دارد.",
        biography: "حافظ در دوران حکومت آل مظفر و تیموریان زندگی می‌کرد. او با وجود فقر مالی، به علم و ادب روی آورد و در محافل علمی و ادبی شیراز شرکت می‌کرد. حافظ به قرآن و حدیث مسلط بود و اشعارش پر از اشارات قرآنی است. او با شاعران معاصر خود مانند سلمان ساوجی مراوده داشت. حافظ در سال ۷۹۲ هجری قمری در زادگاهش شیراز چشم از جهان فروبست و در حافظیه به خاک سپرده شد.",
        img: "../public/img/prof5.png",
        category: "ادبیات"
    },
    {
        id: 2,
        name: "سعدی",
        lastName: "شیرازی",
        fullName: "شیخ مصلح‌الدین سعدی شیرازی",
        birth: "۵۸۵ ه.ق",
        birthPlace: "شیراز",
        death: "۶۹۱ ه.ق",
        deathPlace: "شیراز",
        job: "شاعر، نویسنده",
        era: "اسلامی",
        categories: ["شاعر", "ادیب", "حکیم", "نویسنده"],
        quote: "بنی‌آدم اعضای یکدیگرند که در آفرینش ز یک گوهرند\nچو عضوی به درد آورد روزگار دگر عضوها را نماند قرار",
        shortStory: "شیخ مصلح‌الدین سعدی شیرازی در شیراز متولد شد. او پس از تحصیل در نظامیه بغداد، به سفرهای طولانی در آسیا، اروپا و آفریقا پرداخت و تجربه‌های خود را در 'بوستان' و 'گلستان' به نظم و نثر درآورد.",
        biography: "سعدی در خانواده‌ای روحانی در شیراز به دنیا آمد. پدرش از بزرگان شهر بود اما در کودکی سعدی از دنیا رفت. سعدی ابتدا در شیراز و سپس در بغداد به تحصیل علوم دینی و ادبی پرداخت. او در جوانی به سفرهای زیادی رفت و با فقر، جنگ و حوادث مختلف روبرو شد و همه را در آثارش ثبت کرد. سعدی در سال ۶۹۱ هجری قمری در شیراز وفات یافت.",
        img: "../public/img/prof9.png",
        category: "ادبیات"
    },
    {
        id: 3,
        name: "مولانا",
        lastName: "بلخی",
        fullName: "جلال‌الدین محمد بلخی (مولوی)",
        birth: "۶۰۴ ه.ق",
        birthPlace: "بلخ",
        death: "۶۷۲ ه.ق",
        deathPlace: "قونیه",
        job: "شاعر، عارف",
        era: "اسلامی",
        categories: ["شاعر", "عارف", "فیلسوف", "حکیم"],
        quote: "بشنو از نی چون حکایت می‌کند از جدایی‌ها شکایت می‌کند\nکز نیستان تا مرا ببریده‌اند از نفیرم مرد و زن نالیده‌اند",
        shortStory: "مولانا جلال‌الدین محمد بلخی در سال ۶۰۴ هجری قمری در شهر بلخ به دنیا آمد. خانواده‌اش به دلیل حمله مغول به قونیه مهاجرت کردند. آشنایی با شمس تبریزی زندگی او را دگرگون کرد و شاهکار 'مثنوی معنوی' را خلق کرد.",
        biography: "مولانا در خانواده‌ای از علما و عرفا به دنیا آمد. پدرش بهاءالدین ولد از عرفای بزرگ زمان بود. مولانا در جوانی به تدریس علوم دینی پرداخت. آشنایی با شمس تبریزی در سال ۶۴۲ هجری قمری نقطه عطف زندگی مولانا بود. او مثنوی معنوی را در ۶ دفتر به رشته تحریر درآورد. مولانا در سال ۶۷۲ هجری قمری در قونیه وفات یافت.",
        img: "../public/img/prof2.png",
        category: "ادبیات"
    },
    {
        id: 4,
        name: "فردوسی",
        lastName: "توسی",
        fullName: "ابوالقاسم فردوسی توسی",
        birth: "۳۲۹ ه.ق",
        birthPlace: "توس",
        death: "۴۱۶ ه.ق",
        deathPlace: "توس",
        job: "شاعر، حماسه‌سرا",
        era: "اسلامی",
        categories: ["شاعر", "حماسه‌سرا", "نویسنده", "اسطوره‌شناس"],
        quote: "توانا بود هرکه دانا بود ز دانش دل پیر برنا بود\nستایش خرد را بگویم همی خرد را برانم به گفتار خویش",
        shortStory: "ابوالقاسم فردوسی توسی در سال ۳۲۹ هجری قمری در روستای پاژ در نزدیکی توس به دنیا آمد. او ۳۵ سال از عمر خود را صرف سرودن شاهنامه کرد و زبان فارسی را از نابودی نجات داد.",
        biography: "فردوسی در خانواده‌ای از دهقانان که به فرهنگ ایران باستان علاقه داشتند به دنیا آمد. او از کودکی به داستان‌های کهن علاقه داشت. فردوسی با کمک دقیقی که بخشی از شاهنامه را سروده بود، تصمیم گرفت کل تاریخ ایران را به نظم درآورد. شاهنامه شامل ۶۰ هزار بیت است. فردوسی در سال ۴۱۶ هجری قمری در فقر و تنهایی از دنیا رفت.",
        img: "../public/img/prof1.png",
        category: "ادبیات"
    },
    {
        id: 5,
        name: "خیام",
        lastName: "نیشابوری",
        fullName: "عمر خیام نیشابوری",
        birth: "۴۳۹ ه.ق",
        birthPlace: "نیشابور",
        death: "۵۱۷ ه.ق",
        deathPlace: "نیشابور",
        job: "شاعر، ریاضیدان، ستاره‌شناس",
        era: "اسلامی",
        categories: ["شاعر", "ریاضیدان", "ستاره‌شناس", "فیلسوف", "حکیم"],
        quote: "چندین به جهان سخن ز مخزن برخاست از هر نفسی هزار خرمن برخاست\nبی‌گه بود آنچه ما همی‌بینیم با ما غم خوردن و غم خوران برخاست",
        shortStory: "عمر خیام نیشابوری در سال ۴۳۹ هجری قمری در نیشابور به دنیا آمد. او هم شاعری بزرگ بود و هم ریاضیدانی نابغه. رباعیاتش به زبان‌های مختلف ترجمه شده و شهرت جهانی دارد.",
        biography: "خیام در نیشابور به دنیا آمد و در همان شهر رشد یافت. او از کودکی به ریاضیات و علوم نجومی علاقه داشت و به بغداد و سمرقند سفر کرد. خیام در دربار ملکشاه سلجوقی حضور داشت و تقویم جلالی را تدوین کرد. رباعیات خیام در قرن نوزدهم توسط ادوارد فیتزجرالد به انگلیسی ترجمه شد و شهرت جهانی پیدا کرد.",
        img: "../public/img/prof3.png",
        category: "ادبیات"
    },
    {
        id: 6,
        name: "عطار",
        lastName: "نیشابوری",
        fullName: "فریدالدین عطار نیشابوری",
        birth: "۵۴۰ ه.ق",
        birthPlace: "نیشابور",
        death: "۶۱۸ ه.ق",
        deathPlace: "نیشابور",
        job: "شاعر، عارف",
        era: "اسلامی",
        categories: ["شاعر", "عارف", "حکیم"],
        quote: "چو بشنید از او مرغکان سخن همه گفتند کاین راست چیزی ز من",
        shortStory: "فریدالدین عطار نیشابوری در سال ۵۴۰ هجری قمری در نیشابور متولد شد. او یکی از بزرگ‌ترین شاعران عارف ایرانی و خالق اثر جاودانه 'منطق‌الطیر' است که داستان سفر مرغان به سوی سیمرغ را روایت می‌کند.",
        biography: "عطار در نیشابور به دنیا آمد و در جوانی به شغل عطاری (دارو‌فروشی) مشغول بود. او در کنار کار، به مطالعه علوم دینی و عرفان پرداخت و به یکی از بزرگ‌ترین عرفای زمان خود تبدیل شد. عطار در حمله مغول به نیشابور کشته شد و آرامگاهش در نیشابور قرار دارد.",
        img: "../public/img/prof11.png",
        category: "ادبیات"
    },
    {
        id: 7,
        name: "ناصرخسرو",
        lastName: "قبادیانی",
        fullName: "ناصر خسرو قبادیانی",
        birth: "۳۹۴ ه.ق",
        birthPlace: "بلخ",
        death: "۴۸۱ ه.ق",
        deathPlace: "یمگان",
        job: "شاعر، فیلسوف، جهانگرد",
        era: "اسلامی",
        categories: ["شاعر", "فیلسوف", "جهانگرد", "نویسنده"],
        quote: "هر که را جوینده‌ای باشد، به مقصود خود رسد",
        shortStory: "ناصر خسرو قبادیانی در سال ۳۹۴ هجری قمری در بلخ متولد شد. او یکی از بزرگ‌ترین شاعران و فیلسوفان ایرانی و نویسنده 'سفرنامه' معروف است که حاوی مشاهداتش از سفرهای طولانی به کشورهای اسلامی است.",
        biography: "ناصر خسرو در جوانی به شغل دیوانی مشغول بود اما پس از یک خواب عرفانی، زندگی خود را تغییر داد و به سفر پرداخت. او به مدت هفت سال به کشورهای مختلف سفر کرد و مشاهدات خود را در سفرنامه‌ای ارزشمند ثبت کرد. ناصر خسرو در نهایت به یمگان رفت و تا پایان عمر در آنجا ماند.",
        img: "../public/img/prof12.png",
        category: "ادبیات"
    },
    {
        id: 8,
        name: "منوچهری",
        lastName: "دامغانی",
        fullName: "منوچهری دامغانی",
        birth: "حدود ۴۰۰ ه.ق",
        birthPlace: "دامغان",
        death: "۴۳۲ ه.ق",
        deathPlace: "نامشخص",
        job: "شاعر",
        era: "اسلامی",
        categories: ["شاعر", "ادیب"],
        quote: "ای برده به یک بوسه ز من هوش و خرد را\nوی داده به یک لحظه ز من جان و بدن را",
        shortStory: "منوچهری دامغانی از شاعران بزرگ قرن پنجم هجری و از ستایندگان سلطان محمود غزنوی بود. او به خاطر غزل‌ها و قصیده‌های نابش در ادبیات فارسی شهرت دارد.",
        biography: "منوچهری در دامغان به دنیا آمد و در جوانی به دربار غزنویان پیوست. او شاعری طبیعت‌گرا بود و در اشعارش به توصیف بهار، خزان و طبیعت می‌پرداخت. منوچهری در سال ۴۳۲ هجری قمری درگذشت.",
        img: "../public/img/prof13.png",
        category: "ادبیات"
    },
    {
        id: 9,
        name: "ابن‌سینا",
        lastName: "ابوعلی",
        fullName: "ابوعلی سینا (ابن‌سینا)",
        birth: "۳۵۹ ه.ق",
        birthPlace: "بخارا",
        death: "۴۲۸ ه.ق",
        deathPlace: "همدان",
        job: "پزشک، فیلسوف، دانشمند",
        era: "اسلامی",
        categories: ["پزشک", "فیلسوف", "ریاضیدان", "ستاره‌شناس", "دانشمند"],
        quote: "العالم یدرک بالعقل و لا یدرک بالحس",
        shortStory: "ابوعلی سینا در سال ۳۵۹ هجری قمری در بخارا به دنیا آمد. او در ۱۰ سالگی قرآن را حفظ بود و در ۱۸ سالگی به تمام علوم زمان تسلط پیدا کرد. کتاب 'قانون در پزشکی' او تا قرن ۱۷ میلادی در دانشگاه‌های اروپا تدریس می‌شد.",
        biography: "ابن‌سینا در خانواده‌ای از اهل علم متولد شد. پدرش از دولتمردان سامانی بود. ابن‌سینا در ۱۶ سالگی به مطالعه پزشکی پرداخت و در ۱۸ سالگی به عنوان پزشک دربار منصوب شد. او به شهرهای مختلفی سفر کرد و بیش از ۴۵۰ کتاب تألیف کرد. ابن‌سینا در سال ۴۲۸ هجری قمری در همدان وفات یافت.",
        img: "../public/img/prof6.png",
        category: "علمی"
    },
    {
        id: 12,
        name: "خواجه نصیر",
        lastName: "طوسی",
        fullName: "خواجه نصیرالدین طوسی",
        birth: "۵۹۷ ه.ق",
        birthPlace: "طوس",
        death: "۶۷۲ ه.ق",
        deathPlace: "بغداد",
        job: "فیلسوف، ریاضیدان، ستاره‌شناس",
        era: "اسلامی",
        categories: ["فیلسوف", "ریاضیدان", "ستاره‌شناس", "دانشمند", "متکلم"],
        quote: "العلم سلطان و العلم نور",
        shortStory: "خواجه نصیرالدین طوسی در سال ۵۹۷ هجری قمری در طوس متولد شد. او یکی از بزرگ‌ترین دانشمندان ایرانی است که در فلسفه، ریاضیات، ستاره‌شناسی و کلام صاحب نظر بود. رصدخانه مراغه از ساخته‌های اوست.",
        biography: "نصیرالدین طوسی در طوس به دنیا آمد و از کودکی به علوم مختلف علاقه داشت. او در حمله مغول به ایران، به خدمت هلاکوخان درآمد و رصدخانه مراغه را ساخت. طوسی کتاب 'تذکره فی هیئت' را در ستاره‌شناسی نوشت. او در سال ۶۷۲ هجری قمری در بغداد وفات یافت.",
        img: "../public/img/prof4.png",
        category: "علمی"
    },
    {
        id: 16,
        name: "کوروش",
        lastName: "بزرگ",
        fullName: "کوروش بزرگ (کوروش دوم هخامنشی)",
        birth: "حدود ۶۰۰ ق.م",
        birthPlace: "پاسارگاد",
        death: "۵۳۰ ق.م",
        deathPlace: "پاسارگاد",
        job: "پادشاه، بنیان‌گذار هخامنشیان",
        era: "پیش از اسلام",
        categories: ["پادشاه", "فاتح", "بنیان‌گذار"],
        quote: "من کوروش هستم، پادشاه جهان، پادشاه بزرگ، پادشاه قدرتمند، پادشاه بابل، پادشاه سومر و اکد، پادشاه چهارگوشه جهان",
        shortStory: "کوروش بزرگ در حدود سال ۶۰۰ پیش از میلاد در پاسارگاد متولد شد. او بنیان‌گذار شاهنشاهی هخامنشی است که اولین امپراتوری بزرگ جهان بود. کوروش به خاطر رفتار انسانی‌اش با ملل مغلوب، در تاریخ به عنوان پادشاه بزرگ شناخته می‌شود.",
        biography: "کوروش در خانواده‌ای از پادشاهان پارس به دنیا آمد. او با شورش علیه پادشاه ماد، قدرت را به دست گرفت و سپس امپراتوری بزرگی از هند تا مصر ساخت. کوروش به خاطر منشور حقوق بشر خود (استوانه کوروش) مشهور است. او در سال ۵۳۰ پیش از میلاد در پاسارگاد وفات یافت.",
        img: "../public/img/prof10.png",
        category: "سیاست"
    },
    {
        id: 17,
        name: "داریوش",
        lastName: "بزرگ",
        fullName: "داریوش بزرگ (داریوش اول هخامنشی)",
        birth: "۵۵۰ ق.م",
        birthPlace: "پاسارگاد",
        death: "۴۸۶ ق.م",
        deathPlace: "تخت جمشید",
        job: "پادشاه هخامنشی",
        era: "پیش از اسلام",
        categories: ["پادشاه", "فاتح", "سازنده"],
        quote: "آنچه را که باید انجام شود، با دستان خود انجام می‌دهم",
        shortStory: "داریوش بزرگ در سال ۵۵۰ پیش از میلاد متولد شد. او سومین پادشاه هخامنشی است که امپراتوری را به اوج قدرت رساند. داریوش تخت جمشید را ساخت و حکومتی منظم در امپراتوری ایجاد کرد.",
        biography: "داریوش از خاندان هخامنشی بود و پس از مرگ کمبوجیه به پادشاهی رسید. او امپراتوری را به ۲۰ ساتراپی تقسیم کرد و شبکه جاده‌ها را گسترش داد. داریوش در سال ۴۸۶ پیش از میلاد در تخت جمشید وفات یافت.",
        img: "../public/img/prof12.png",
        category: "سیاست"
    },
    {
        id: 19,
        name: "شاه عباس",
        lastName: "کبیر",
        fullName: "شاه عباس اول صفوی (شاه عباس کبیر)",
        birth: "۹۷۸ ه.ق",
        birthPlace: "هرات",
        death: "۱۰۳۸ ه.ق",
        deathPlace: "اشتهارد، مازندران",
        job: "پادشاه صفوی",
        era: "صفوی",
        categories: ["پادشاه", "فاتح", "سازنده", "مصلح"],
        quote: "کشور با عدالت آباد می‌شود، نه با جنگ",
        shortStory: "شاه عباس در سال ۹۷۸ هجری قمری در هرات متولد شد. او بزرگ‌ترین پادشاه صفوی است که ایران را به اوج قدرت و شکوه رساند. پایتخت را به اصفهان منتقل کرد و بناهای باشکوهی در آن شهر ساخت.",
        biography: "شاه عباس در هرات به دنیا آمد و در جوانی با کمک قزلباش‌ها به سلطنت رسید. او با عثمانی‌ها و ازبک‌ها جنگید و ایران را از تهاجم آنان نجات داد. شاه عباس روابط تجاری با اروپا برقرار کرد و به هنر و معماری رونق بخشید. او در سال ۱۰۳۸ هجری قمری در مازندران وفات یافت.",
        img: "../public/img/prof14.png",
        category: "سیاست"
    },
    {
        id: 20,
        name: "نادرشاه",
        lastName: "افشار",
        fullName: "نادرشاه افشار (نادر قلی بیگ)",
        birth: "۱۶۸۸ م",
        birthPlace: "درگز، خراسان",
        death: "۱۷۴۷ م",
        deathPlace: "قوچان، خراسان",
        job: "پادشاه، فاتح",
        era: "افشاریه",
        categories: ["پادشاه", "فاتح", "سردار"],
        quote: "من نادر هستم، از کوه‌های خراسان برخاسته‌ام",
        shortStory: "نادرشاه افشار در سال ۱۶۸۸ میلادی در درگز خراسان متولد شد. او یکی از بزرگ‌ترین سرداران تاریخ ایران است که از صفویان دفاع کرد و سپس به پادشاهی رسید. نادرشاه هند را فتح کرد و غنایم زیادی به ایران آورد.",
        biography: "نادرشاه در خانواده‌ای فقیر در خراسان به دنیا آمد. او در جوانی به خدمت درآمد و با شجاعت خود به مقامات بالایی رسید. نادرشاه افغان‌ها را از ایران بیرون کرد و صفویان را دوباره به سلطنت رساند. او سپس خود به پادشاهی رسید و به هند لشکر کشید. نادرشاه در سال ۱۷۴۷ میلادی در قوچان به قتل رسید.",
        img: "../public/img/prof17.png",
        category: "سیاست"
    },
    {
        id: 26,
        name: "پروین",
        lastName: "اعتصامی",
        fullName: "پروین اعتصامی",
        birth: "۱۲۸۵ شمسی",
        birthPlace: "تبریز",
        death: "۱۳۲۰ شمسی",
        deathPlace: "تهران",
        job: "شاعر",
        era: "معاصر",
        categories: ["شاعر", "نویسنده"],
        quote: "میان شاخه‌های نورس، به بوی صبحدم\nچنین شنیده‌ام از بلبلان بهار:",
        shortStory: "پروین اعتصامی در سال ۱۲۸۵ شمسی در تبریز متولد شد. او یکی از بزرگ‌ترین شاعران زن ایران است که در قالب قصیده و مثنوی، اشعار نابی سروده است. اشعار پروین پر از پند و اندرز و مفاهیم اخلاقی است.",
        biography: "پروین در تبریز به دنیا آمد و در تهران تحصیل کرد. پدرش یوسف اعتصامی از ادیبان بزرگ بود و او را در سرودن شعر تشویق کرد. پروین در ۱۸ سالگی اولین اشعار خود را منتشر کرد و خیلی زود به شهرت رسید. او در سال ۱۳۲۰ شمسی در تهران وفات یافت.",
        img: "../public/img/prof8.png",
        category: "ادبیات"
    }
];

function renderCards(data) {
    const container = document.getElementById('cardsContainer');
    if (!container) return;
    
    container.innerHTML = '';

    if (data.length === 0) {
        container.innerHTML = `
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h4>هیچ مشاهیری یافت نشد</h4>
                <p>لطفاً فیلترهای خود را تغییر دهید یا جستجوی دیگری انجام دهید.</p>
            </div>
        `;
        return;
    }

    data.forEach(figure => {
        const card = document.createElement('div');
        card.innerHTML = `
            <div class="prof-card" onclick="showProfile(${figure.id})">
                <img src="${figure.img}" class="card-img-top" alt="${figure.name}" 
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22220%22%3E%3Crect width=%22400%22 height=%22220%22 fill=%22%23d6c9bd%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%237b6250%22 font-size=%2224%22 font-family=%22sans-serif%22%3E${figure.name}%3C/text%3E%3C/svg%3E'">
                <div class="card-body">
                    <h5 class="card-title">${figure.name} ${figure.lastName}</h5>
                    <div class="card-subtitle">${figure.fullName}</div>
                    <p class="card-text">${figure.shortStory.substring(0, 60)}...</p>
                    <div class="card-date">
                        <i class="far fa-calendar-alt"></i> ${figure.birth} - ${figure.death}
                    </div>
                    <div>
                        ${figure.categories.slice(0, 3).map(cat => `<span class="badge-tag">${cat}</span>`).join(' ')}
                        ${figure.categories.length > 3 ? `<span class="badge-tag">+${figure.categories.length - 3}</span>` : ''}
                    </div>
                </div>
                <div class="card-overlay">
                    <span><i class="fas fa-eye me-2"></i> مشاهده پروفایل</span>
                </div>
            </div>
        `;
        container.appendChild(card);
    });

    data.forEach(figure => {
        const card = document.createElement('div');
        card.className = 'celebrity-card';
        card.onclick = function () { showProfile(figure.id); };
        card.innerHTML = `
            <img src="${figure.img}" class="avatar" alt="${figure.name}">
            <div class="info">
                <div class="name">${figure.name} ${figure.lastName}</div>
                <div class="title">${figure.fullName}</div>
                <div class="date"><i class="far fa-calendar-alt me-1"></i> ${figure.birth} - ${figure.death}</div>
            </div>
            <button class="view-btn" onclick="event.stopPropagation(); showProfile(${figure.id})">
                <i class="fas fa-eye me-1"></i> مشاهده
            </button>
        `;
        container.appendChild(card);
    });
}

function getSelectedFilters() {
    const checked = document.querySelectorAll('.form-check-input:checked');
    const selected = [];
    checked.forEach(cb => {
        if (cb.value) selected.push(cb.value);
    });
    return selected;
}

function updateFilterCount() {
    const filterCount = document.getElementById('filterCount');
    if (!filterCount) return;
    const count = document.querySelectorAll('.form-check-input:checked').length;
    filterCount.textContent = count;
}

function filterCards() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    
    const searchQuery = searchInput.value.toLowerCase();
    const selectedFilters = getSelectedFilters();

    const filtered = figures.filter(figure => {
        const matchSearch =
            figure.name.includes(searchQuery) ||
            figure.lastName.includes(searchQuery) ||
            figure.fullName.includes(searchQuery) ||
            figure.categories.some(c => c.includes(searchQuery)) ||
            figure.birthPlace.includes(searchQuery);

        let matchFilters = true;
        if (selectedFilters.length > 0) {
            matchFilters = figure.categories.some(cat => selectedFilters.includes(cat)) ||
                selectedFilters.includes(figure.era) ||
                selectedFilters.includes(figure.birthPlace);
        }

        return matchSearch && matchFilters;
    });

    renderCards(filtered);
    updateFilterCount();
}

function applyFilters() {
    filterCards();
    const dropdown = document.getElementById('filterDropdown');
    if (dropdown) dropdown.classList.remove('show');
}

function clearFilters() {
    document.querySelectorAll('.form-check-input').forEach(cb => cb.checked = false);
    const searchInput = document.getElementById('searchInput');
    if (searchInput) searchInput.value = '';
    renderCards(figures);
    updateFilterCount();
    const dropdown = document.getElementById('filterDropdown');
    if (dropdown) dropdown.classList.remove('show');
}

function toggleFilter() {
    const dropdown = document.getElementById('filterDropdown');
    if (dropdown) dropdown.classList.toggle('show');
}

function showProfile(id) {
    //BUG FIX
    history.pushState(
        { profile: id },
        "",
        "?profile=" + id
    );
    
    const figure = figures.find(f => f.id === id);
    if (!figure) {
        alert('مشاهیر یافت نشد!');
        return;
    }

    const listPage = document.getElementById('listPage');
    const profilePage = document.getElementById('profilePage');
    const footerSection = document.getElementById('footerSection');
    
    if (listPage) listPage.style.display = 'none';
    if (profilePage) {
        profilePage.style.display = 'block';
        profilePage.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    if (footerSection) footerSection.style.display = 'none';

    const container = document.getElementById('profileContent');
    if (!container) return;

    const desktopHTML = `
        <div class="profile-desktop">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card-shadow p-4 h-100"> 
                        <div class="profile-img-wrapper">
                            <img src="${figure.img}" alt="${figure.name}" class="img-fluid">
                        </div>
                        <div class="mt-3">
                            <span class="top-badge"><i class="fas fa-chess-queen me-1"></i> اطلاعات کلی</span>
                            <div class="poet-name mt-2">${figure.name} <small>${figure.lastName}</small></div>
                            <div class="profile-fullname">${figure.fullName}</div>
                        </div>
                        <div class="row g-2 mt-3">
                            <div class="col-6"><span class="info-label"><i class="far fa-calendar-alt me-1"></i> تاریخ تولد</span><br><span class="info-value">${figure.birth}</span></div>
                            <div class="col-6"><span class="info-label"><i class="fas fa-map-pin me-1"></i> محل تولد</span><br><span class="info-value">${figure.birthPlace}</span></div>
                            <div class="col-6"><span class="info-label"><i class="fas fa-skull me-1"></i> تاریخ وفات</span><br><span class="info-value">${figure.death}</span></div>
                            <div class="col-6"><span class="info-label"><i class="fas fa-map-pin me-1"></i> محل وفات</span><br><span class="info-value">${figure.deathPlace}</span></div>
                            <div class="col-6"><span class="info-label"><i class="fas fa-briefcase me-1"></i> حرفه</span><br><span class="info-value">${figure.job}</span></div>
                            <div class="col-6"><span class="info-label"><i class="fas fa-history me-1"></i> دوره تاریخی</span><br><span class="info-value">${figure.era}</span></div>
                        </div>
                        <hr class="divider-light">
                        <div class="tag-group">
                            ${figure.categories.map(cat => `<span class="badge"><i class="fas fa-tag"></i> ${cat}</span>`).join(' ')}
                        </div>
                        <div class="verse-box mt-3">
                            <i class="fas fa-quote-right"></i> ${figure.quote.replace(/\n/g, '<br><i class="fas fa-quote-left"></i> ')}
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card-shadow1 p-4 h-100">
                        <div class="profile-intro-card">
                            <div class="bio-title intro-title"><i class="fas fa-scroll"></i> معرفی</div>
                            <p class="intro-text"><strong>${figure.fullName}</strong> · ${figure.shortStory}</p>
                            <p class="intro-full">${figure.biography}</p>
                        </div>

                        <div class="profile-footer-info">
                            <span class="info-tag"><i class="fas fa-map-marker-alt"></i> ${figure.birthPlace}</span>
                            <span class="info-tag"><i class="fas fa-calendar-alt"></i> ${figure.birth} – ${figure.death}</span>
                            <span class="info-tag"><i class="fas fa-feather"></i> ${figure.job}</span>
                            <span class="info-tag"><i class="fas fa-tag"></i> ${figure.era}</span>
                        </div>

                        <div class="bio-timeline">
                            <div class="bio-item">
                                <div class="bio-icon"><i class="fas fa-birthday-cake"></i></div>
                                <div class="bio-content">
                                    <h5>تولد در ${figure.birthPlace}</h5>
                                    <p>${figure.fullName} در سال ${figure.birth} در ${figure.birthPlace} دیده به جهان گشود.</p>
                                </div>
                            </div>
                            <div class="bio-item">
                                <div class="bio-icon"><i class="fas fa-pen-nib"></i></div>
                                <div class="bio-content">
                                    <h5>آغاز فعالیت</h5>
                                    <p>از جوانی به علم و دانش علاقه‌مند شد و در مسیر تخصص خود گام برداشت.</p>
                                </div>
                            </div>
                            <div class="bio-item">
                                <div class="bio-icon"><i class="fas fa-crown"></i></div>
                                <div class="bio-content">
                                    <h5>شهرت و استقبال</h5>
                                    <p>با آثار ارزشمند خود به شهرت رسید و مورد احترام مردم و بزرگان زمان شد.</p>
                                </div>
                            </div>
                            <div class="bio-item">
                                <div class="bio-icon"><i class="fas fa-dove"></i></div>
                                <div class="bio-content">
                                    <h5>وفات در ${figure.deathPlace}</h5>
                                    <p>در سال ${figure.death} در ${figure.deathPlace} دیده از جهان فرو بست.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    const mobileHTML = `
        <div class="profile-mobile">
            <div class="profile-header-mobile">
                <img src="${figure.img}" alt="${figure.name}">
                <div class="profile-overlay">
                    <h1>${figure.name} ${figure.lastName}</h1>
                    <div class="subtitle">${figure.fullName}</div>
                    <div class="date-badge">
                        <i class="far fa-calendar-alt me-1"></i> ${figure.birth} - ${figure.death}
                    </div>
                </div>
            </div>

            <div class="profile-job-card">
                <p><i class="fas fa-briefcase"></i> <strong>${figure.job}</strong></p>
            </div>

            <div class="stats-grid-mobile">
                <div class="stat-item-mobile">
                    <i class="fas fa-feather-alt stat-icon"></i>
                    <div class="stat-label">سبک</div>
                    <div class="stat-value">${figure.categories[0] || 'سنتی'}</div>
                </div>
                <div class="stat-item-mobile">
                    <i class="fas fa-globe stat-icon"></i>
                    <div class="stat-label">شهرت</div>
                    <div class="stat-value">جهانی</div>
                </div>
                <div class="stat-item-mobile">
                    <i class="fas fa-award stat-icon"></i>
                    <div class="stat-label">آثار</div>
                    <div class="stat-value">ارزشمند</div>
                </div>
                <div class="stat-item-mobile">
                    <i class="fas fa-clock stat-icon"></i>
                    <div class="stat-label">میراث</div>
                    <div class="stat-value">ماندگار</div>
                </div>
            </div>

            <div class="profile-info-grid-mobile">
                <div class="info-box">
                    <span class="label">تاریخ تولد</span>
                    <div class="value">${figure.birth}</div>
                </div>
                <div class="info-box">
                    <span class="label">محل تولد</span>
                    <div class="value">${figure.birthPlace}</div>
                </div>
                <div class="info-box">
                    <span class="label">تاریخ وفات</span>
                    <div class="value">${figure.death}</div>
                </div>
                <div class="info-box">
                    <span class="label">محل وفات</span>
                    <div class="value">${figure.deathPlace}</div>
                </div>
            </div>

            <div class="profile-quote-mobile">
                <p><i class="fas fa-quote-right"></i> ${figure.quote.replace(/\n/g, '<br>')}</p>
            </div>

            <div class="bio-card-mobile">
                <div class="bio-title"><i class="fas fa-scroll"></i> زندگینامه</div>
                <p><strong>${figure.fullName}</strong></p>
                <p>${figure.biography}</p>
            </div>

            <div class="profile-tags-mobile">
                ${figure.categories.map(cat => `
                    <span class="tag"><i class="fas fa-tag"></i> ${cat}</span>
                `).join(' ')}
            </div>
        </div>
    `;

    container.innerHTML = desktopHTML + mobileHTML;
}

function showList() {
    const listPage = document.getElementById('listPage');
    const profilePage = document.getElementById('profilePage');
    const footerSection = document.getElementById('footerSection');
    
    if (profilePage) profilePage.style.display = 'none';
    if (listPage) listPage.style.display = 'block';
    if (footerSection) footerSection.style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function getPageFromURL() {
    const params = new URLSearchParams(window.location.search);
    return params.get('page') || 'about';
}

function showPage(page) {
    const pageContents = document.querySelectorAll('.page-content');
    if (pageContents.length === 0) return;
    
    pageContents.forEach(el => {
        el.classList.remove('active');
    });

    const target = document.getElementById('page' + page.charAt(0).toUpperCase() + page.slice(1));
    if (target) {
        target.classList.add('active');
    }

    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    navLinks.forEach(link => {
        link.classList.remove('active');
    });

    const navMap = {
        'about': 'navAbout',
        'contact': 'navContact'
    };
    const navId = navMap[page];
    if (navId) {
        const navElement = document.getElementById(navId);
        if (navElement) navElement.classList.add('active');
    }

    localStorage.setItem('currentPage', page);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function navigateToCategory(categoryName) {
    window.location.href = 'profile.php?category=' + categoryName;
}

function updateCountdowns() {
    const countdowns = document.querySelectorAll('.countdown');
    if (countdowns.length === 0) return;
    
    countdowns.forEach(function(countdown) {
        const targetDate = new Date(countdown.dataset.date).getTime();
        const now = new Date().getTime();
        const distance = targetDate - now;

        if (distance < 0) {
            countdown.querySelector('.days').textContent = '00';
            countdown.querySelector('.hours').textContent = '00';
            countdown.querySelector('.minutes').textContent = '00';
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

        countdown.querySelector('.days').textContent = String(days).padStart(2, '0');
        countdown.querySelector('.hours').textContent = String(hours).padStart(2, '0');
        countdown.querySelector('.minutes').textContent = String(minutes).padStart(2, '0');
    });
}

const pageParam = getPageFromURL();
if (pageParam === 'about' || pageParam === 'contact') {
    showPage(pageParam);
}

document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('cardsContainer')) {
        renderCards(figures);
        updateFilterCount();
        const searchInput = document.getElementById('searchInput');
        if (searchInput) searchInput.addEventListener('keyup', filterCards);
    }

    if (document.querySelectorAll('.page-content').length > 0) {
        const savedPage = localStorage.getItem('currentPage') || 'about';
        showPage(savedPage);
    }

    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('contactName');
            const email = document.getElementById('contactEmail');
            const subject = document.getElementById('contactSubject');
            const message = document.getElementById('contactMessage');

            let isValid = true;
            let errors = [];

            if (!name.value.trim()) {
                isValid = false;
                errors.push('❌ لطفاً نام خود را وارد کنید.');
                name.style.borderColor = '#dc3545';
            } else {
                name.style.borderColor = '#e6ddd5';
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email.value.trim() || !emailRegex.test(email.value)) {
                isValid = false;
                errors.push('❌ لطفاً یک ایمیل معتبر وارد کنید.');
                email.style.borderColor = '#dc3545';
            } else {
                email.style.borderColor = '#e6ddd5';
            }

            if (!subject.value) {
                isValid = false;
                errors.push('❌ لطفاً موضوع را انتخاب کنید.');
                subject.style.borderColor = '#dc3545';
            } else {
                subject.style.borderColor = '#e6ddd5';
            }

            if (!message.value.trim()) {
                isValid = false;
                errors.push('❌ لطفاً متن پیام را وارد کنید.');
                message.style.borderColor = '#dc3545';
            } else {
                message.style.borderColor = '#e6ddd5';
            }

            if (!isValid) {
                alert('⚠️ خطا در ارسال پیام:\n\n' + errors.join('\n'));
                return;
            }

            alert(
                '✅ پیام شما با موفقیت ارسال شد!\n\n' +
                'نام: ' + name.value + '\n' +
                'ایمیل: ' + email.value + '\n' +
                'موضوع: ' + subject.options[subject.selectedIndex].text + '\n' +
                'پیام: ' + message.value.substring(0, 50) + '...\n\n' +
                '📢 توجه: این یک نمونه نمایشی است.'
            );

            contactForm.reset();
            document.querySelectorAll('.form-control').forEach(el => {
                el.style.borderColor = '#e6ddd5';
            });
        });
    }

    const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(el => new bootstrap.Tooltip(el));

    const popovers = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popovers.map(el => new bootstrap.Popover(el));

    window.showToast = function() {
        const toastEl = document.getElementById('liveToast');
        if (toastEl) {
            new bootstrap.Toast(toastEl, { delay: 3000 }).show();
        }
    };

    window.showErrorToast = function() {
        const toastEl = document.getElementById('errorToast');
        if (toastEl) {
            new bootstrap.Toast(toastEl, { delay: 3000 }).show();
        }
    };

    const scrollSpyEl = document.querySelector('[data-bs-spy="scroll"]');
    if (scrollSpyEl) {
        new bootstrap.ScrollSpy(scrollSpyEl, { target: '#productNav' });
    }

    const toggleBtn = document.getElementById('filterToggleBtn');
    const sidebar = document.getElementById('filterSidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            if (sidebar.style.display === 'none' || sidebar.style.display === '') {
                sidebar.style.display = 'block';
                toggleBtn.innerHTML = '<i class="fas fa-times"></i> بستن';
            } else {
                sidebar.style.display = 'none';
                toggleBtn.innerHTML = '<i class="fas fa-filter"></i> فیلترها';
            }
        });

        sidebar.addEventListener('click', function(e) {
            if (e.target === this) {
                sidebar.style.display = 'none';
                toggleBtn.innerHTML = '<i class="fas fa-filter"></i> فیلترها';
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    const selectedCat = urlParams.get('category');
    if (selectedCat) {
        const checkbox = document.querySelector('input[type="checkbox"][value="' + selectedCat + '"]');
        if (checkbox) {
            checkbox.checked = true;
            if (typeof filterCards === 'function') {
                filterCards();
            }
        } else {
            console.warn('چک‌باکسی با مقدار "' + selectedCat + '" پیدا نشد.');
        }
    }

    updateCountdowns();
    setInterval(updateCountdowns, 60000);

    console.log('✅ همه اجزا با موفقیت مقداردهی شدند!');
});
//BUG FIX
window.addEventListener("popstate", function () {

    const params = new URLSearchParams(location.search);

    if (params.has("profile")) {
        showProfile(parseInt(params.get("profile")));
    } else {
        showList();
    }

});