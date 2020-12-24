#!/usr/bin/env bash
php-scoper add-prefix

prefix="WenpriseSpaceNameVendor"
namespaces=("Illuminate" "GuzzleHttp" "Omnipay" "Symfony")

# 替换代码注释文档中的命名空间
for i in "${namespaces[@]}"; do
  #grep "@.* \\\Illuminate" -rl build | xargs sed -i '' -E "s/(@.*) \\\Illuminate/'\1 \\\WpufAlipayVendor\\\Illuminate/g"
  grep "@.* \\\\$i" -rl build | xargs sed -i '' -E "s/(@.*) \\\\$i/\1 \\\\$prefix\\\\$i/g"
done

# 替换其他命名空间
grep "'Nette\\\\" -rl build | xargs sed -i '' "s/'Nette\\\\/'$prefix\\\\\\\Nette\\\\/g"
grep "'Wenprise\\\\" -rl build | xargs sed -i '' "s/'Wenprise\\\\/'$prefix\\\\\\\Wenprise\\\\/g"

grep "|\\\Illuminate" -rl build | xargs sed -i '' "s/|\\\Illuminate/'|\\\\$prefix\\\Illuminate/g"
grep "'\\\\\\\Omnipay" -rl build | xargs sed -i '' "s/'\\\\\\\Omnipay/'\\\\\\\\$prefix\\\\\\\Omnipay/g"

rm -rf vendor/*
composer dump-autoload
