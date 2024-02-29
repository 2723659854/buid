## 8. 值映射

### 首页元素
- 原版
```json
'"type":"BANNER"',
'"type":"LARGE_CARD"',
'"type":"SMALL_CARD"',
'"type":"PRODUCT_LIST"',
```
- 混淆后
```json
'"type":"son"',
'"type":"false"',
'"type":"daughter"',
'"type":"father"',
```

### 产品详情认证项目列表
- 原版
```json
'"taskType":"public"',
'"taskType":"personal"',
'"taskType":"job"',
'"taskType":"ext"',
'"taskType":"bank"',
```
- 混淆后
```json
'"taskType":"passes"',
'"taskType":"washing"',
'"taskType":"ride"',
'"taskType":"thieving"',
'"taskType":"skimpy"',
```

### 认证项组件
- 原版
```json
'"cate":"enum"',
'"cate":"txt"',
'"cate":"citySelect"',
```
- 混淆后
```json
'"cate":"gave"',
'"cate":"bank"',
'"cate":"bulge"',
```

### 原生页
- 原版
```
xx://xxxx/setting     设置
xx://xxxx/main     首页
xx://xxxx/login     登录
xx://xxxx/order?tab=1    订单  0全部，1进行中，2待还，3已完成
xx://xxxx/productDetail?product_id=2    产品详情
```
- 混淆后
```
dompat://dompat.rupiah.i/murk     设置
dompat://dompat.rupiah.i/common     首页
dompat://dompat.rupiah.i/drinking    登录
dompat://dompat.rupiah.i/straightening?paws=1    订单  0全部，1进行中，2待还，3已完成
dompat://dompat.rupiah.i/hope?loam=2   产品详情
```