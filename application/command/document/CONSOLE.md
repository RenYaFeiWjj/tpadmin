#每十分钟检查未支付订单库存及恢复
*/10 * * * * cd /var/www/html/dakou_publish/application/command/shell && ./order_goods_stock.sh  >> /data/log/dakou_publish/order_goods_stock$(date +"\%Y-\%m-\%d").log 2>&1
#每晚12点05记录库存及执行清库存及下架
05 0 * * * cd /var/www/html/dakou_publish/application/command/shell && ./clear_sum_stock.sh  >> /data/log/dakou_publish/clear_sum_stock$(date +"\%Y-\%m-\%d").log 2>&1