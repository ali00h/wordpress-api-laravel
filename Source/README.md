# First Deploy

**Step 1:**
In your local change BASE_URL in .env file:
```
BASE_URL=http://localhost:8000/api/
```
It is used for ajax calling in vue

**Step 2:**

Call:
```
http://127.0.0.1:8000/api/test/123
```
Run:
```
npm run build
```


**Step 3:**
- [ ] Copy and paste all files except node_modules directory in public_html
- [ ] Rename public_html/bootstrap/cache/config.php To public_html/bootstrap/cache/config_old.php 
- [ ] Move public_html/public/index.php to public_html/index.php and change to this:
```
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
```
- [ ] Copy public_html/public/_nuxt directory to public_html/_nuxt
- [ ] Call:
```
http://localhost:8000/api/test/1234
```

# Update Front Deploy
- [ ] Run:
```
npm run build
```
- [ ] Upload public/_nuxt From local To server
- [ ] Copy public_html/public/_nuxt directory to public_html/_nuxt
