<?php
/**
 * Predefine.php
 * Date: 16/7/20
 * Time: 上午10:07
 * Created by Caojiayuan
 */

if (!defined('SEX_MALE')) {// 男
  define('SEX_MALE', 0);
}

if (!defined('SEX_FEMALE')) {// 女
  define('SEX_FEMALE', 1);
}

/*        上班状态        */
if (!defined('RECEIVE_CLOSE')) {
  define('RECEIVE_CLOSE', false);
}

if (!defined('RECEIVE_OPEN')) {
  define('RECEIVE_OPEN', true);
}


/*        行程类型        */
if (!defined('TYPE_LOCAL')) {//市内
  define('TYPE_LOCAL', 0);
}

if (!defined('TYPE_JOURNEY')) {//长途顺路
  define('TYPE_JOURNEY', 1);
}

if (!defined('TYPE_JOURNEY_SPECIAL')) {//长途专线
  define('TYPE_JOURNEY_SPECIAL', 6);
}

if (!defined('TYPE_JOURNEY_ONLY')) {//长途包车
  define('TYPE_JOURNEY_ONLY', 7);
}

if (!defined('TYPE_CHAUFFEUR')) {//代驾
  define('TYPE_CHAUFFEUR', 2);
}

if (!defined('TYPE_CHAUFFEUR_JOURNEY')) {//长途代驾
  define('TYPE_CHAUFFEUR_JOURNEY', 3);
}

if (!defined('TYPE_TRUCK')) {//货车
  define('TYPE_TRUCK', 4);
}

if (!defined('TYPE_CAR_WASH')) {
  define('TYPE_CAR_WASH', 5);
}

/*         行程状态        */
/**
 * 行程已发布
 */
if (!defined('TRIP_PUBLISHED')) {
  define('TRIP_PUBLISHED', 0);
}

/**
 * 行程已接受
 */
if (!defined('TRIP_ACCEPTED')) {
  define('TRIP_ACCEPTED', 1);
}

/**
 * 到达乘客位置或顺风车已预付款
 */
if (!defined('TRIP_IN_POSITION')) {
  define('TRIP_IN_POSITION', 2);
}
/**
 * 行程已进行
 */
if (!defined('TRIP_ACTIVE')) {
  define('TRIP_ACTIVE', 3);
}

/**
 * 行程已完成
 */
if (!defined('TRIP_FINISHED')) {
  define('TRIP_FINISHED', 4);
}

/**
 * 行程已付费
 */
if (!defined('TRIP_PAYED')) {
  define('TRIP_PAYED', 5);
}

/**
 * 行程已评论
 */
if (!defined('TRIP_COMMENTED')) {
  define('TRIP_COMMENTED', 6);
}

/**
 * 行程已取消
 */
if (!defined('TRIP_CANCELED')) {
  define('TRIP_CANCELED', 7);
}

/**
 * 订单已创建
 */
if (!defined('ORDER_ORDERED')) {
  define('ORDER_ORDERED', 0);
}

/**
 * 订单已完成
 */
if (!defined('ORDER_FINISHED')) {
  define('ORDER_FINISHED', 1);
}

/**
 * 订单已取消
 */
if (!defined('ORDER_CANCELED')) {
  define('ORDER_CANCELED', 2);
}


if (!defined('CERT_UNREVIEWED')) {//未审核
  define('CERT_UNREVIEWED', 0);
}

if (!defined('CERT_REVIEWED')) {//已审核
  define('CERT_REVIEWED', 1);
}

if (!defined('CERT_FAILED')) {//审核失败
  define('CERT_FAILED', 2);
}

if (!defined('CERT_CAR')) {//认证汽车
  define('CERT_CAR', 0);
}

if (!defined('CERT_CHAUFFEUR')) {//认证代驾
  define('CERT_CHAUFFEUR', 1);
}

if (!defined('CERT_TRUCK')) {//认证货车
  define('CERT_TRUCK', 2);
}

if (!defined('CERT_SHOP')) {//认证商店
  define('CERT_SHOP', 3);
}

if (!defined('CERT_FIRST_TRADER')) {// 一级代理商
  define('CERT_FIRST_TRADER', 4);
}

if (!defined('CERT_SECOND_TRADER')) {// 二级代理商
  define('CERT_SECOND_TRADER', 5);
}

if (!defined('CERT_JOURNEY')) {// 认证顺风车
  define('CERT_JOURNEY', 6);
}

if (!defined('ROLE_PASSENGER')) {// 乘客
  define('ROLE_PASSENGER', 0);
}

if (!defined('ROLE_DRIVER')) {// 司机
  define('ROLE_DRIVER', 1);
}

if (!defined('DEVICE_ANDROID')) {// 安卓
  define('DEVICE_ANDROID', 0);
}

if (!defined('DEVICE_IOS')) {// ios
  define('DEVICE_IOS', 1);
}


if (!defined('PAYMENT_RECHARGE')) {// 充值
  define('PAYMENT_RECHARGE', 0);
}

if (!defined('PAYMENT_PAY')) {// 付款
  define('PAYMENT_PAY', 1);
}

if (!defined('CAR_MODEL_A')) {// A车
  define('CAR_MODEL_A', 0);
}

if (!defined('CAR_MODEL_B')) {// B车
  define('CAR_MODEL_B', 1);
}

if (!defined('CAR_MODEL_C')) {// C车
  define('CAR_MODEL_C', 2);
}

if (!defined('CAR_MODEL_S')) {// 商务车
  define('CAR_MODEL_S', 3);
}


/////////////Push//////////////////
if (!defined('PUSH_LOC_PUBLISHED')) {// 市内推送新行程
  define('PUSH_LOC_PUBLISHED', 0);
}

if (!defined('PUSH_LOC_ACCEPTED')) {// 市内推送已接受
  define('PUSH_LOC_ACCEPTED', 1);
}

if (!defined('PUSH_LOC_IN_POSITION')) {// 市内推送到达位置
  define('PUSH_LOC_IN_POSITION', 2);
}

if (!defined('PUSH_LOC_ACTIVE')) {// 市内推送开始行程
  define('PUSH_LOC_ACTIVE', 3);
}

if (!defined('PUSH_LOC_FINISHED')) {// 市内推送完成行程
  define('PUSH_LOC_FINISHED', 4);
}

if (!defined('PUSH_LOC_PAS_CANCELED')) {// 市内推送乘客取消
  define('PUSH_LOC_PAS_CANCELED', 5);
}

if (!defined('PUSH_LOC_DIR_CANCELED')) {// 市内推送司机取消
  define('PUSH_LOC_DIR_CANCELED', 6);
}


if (!defined('PUSH_JOU_ACCEPTED')) {// 顺风车已接受
  define('PUSH_JOU_ACCEPTED', 7);
}

if (!defined('PUSH_JOU_PRE_PAYED')) {//已预付款
  define('PUSH_JOU_PRE_PAYED', 8);
}

if (!defined('PUSH_JOU_ACTIVE')) {//开始行程
  define('PUSH_JOU_ACTIVE', 9);
}
if (!defined('PUSH_JOU_FINISHED')) {//完成行程
  define('PUSH_JOU_FINISHED', 10);
}

if (!defined('PUSH_JOU_PAS_CANCELED')) {// 顺风车乘客取消
  define('PUSH_JOU_PAS_CANCELED', 11);
}

if (!defined('PUSH_JOU_DIR_CANCELED')) {// 顺风车司机取消
  define('PUSH_JOU_DIR_CANCELED', 12);
}


if (!defined('PUSH_TRUCK_ACCEPTED')) {// 货车已接受
  define('PUSH_TRUCK_ACCEPTED', 13);
}

if (!defined('PUSH_TRUCK_PRE_PAYED')) {// 货车预付款
  define('PUSH_TRUCK_PRE_PAYED', 14);
}

if (!defined('PUSH_TRUCK_ACTIVE')) {// 货车开始
  define('PUSH_TRUCK_ACTIVE', 15);
}

if (!defined('PUSH_TRUCK_FINISHED')) {// 货车完成
  define('PUSH_TRUCK_FINISHED', 16);
}

if (!defined('PUSH_TRUCK_PAS_CANCELED')) {// 货车乘客取消
  define('PUSH_TRUCK_PAS_CANCELED', 17);
}

if (!defined('PUSH_TRUCK_DIR_CANCELED')) {// 货车司机取消
  define('PUSH_TRUCK_DIR_CANCELED', 18);
}


if (!defined('PUSH_CHA_ACCEPTED')) {// 酒后代驾已接受
  define('PUSH_CHA_ACCEPTED', 19);
}

if (!defined('PUSH_CHA_IN_POSITION')) {// 酒后到达位置
  define('PUSH_CHA_IN_POSITION', 20);
}

if (!defined('PUSH_CHA_ACTIVE')) {// 酒后开始
  define('PUSH_CHA_ACTIVE', 21);
}

if (!defined('PUSH_CHA_FINISHED')) {// 酒后完成
  define('PUSH_CHA_FINISHED', 22);
}

if (!defined('PUSH_CHA_PAS_CANCELED')) {// 酒后乘客取消
  define('PUSH_CHA_PAS_CANCELED', 23);
}

if (!defined('PUSH_CHA_DIR_CANCELED')) {// 酒后司机取消
  define('PUSH_CHA_DIR_CANCELED', 24);
}


if (!defined('PUSH_CHA_JOU_ACCEPTED')) {// 长途代驾已接受
  define('PUSH_CHA_JOU_ACCEPTED', 25);
}

if (!defined('PUSH_CHA_JOU_PRE_PAYED')) {//长途代驾预付款
  define('PUSH_CHA_JOU_PRE_PAYED', 26);
}

if (!defined('PUSH_CHA_JOU_ACTIVE')) {//长途代驾开始行程
  define('PUSH_CHA_JOU_ACTIVE', 27);
}

if (!defined('PUSH_CHA_JOU_FINISHED')) {//长途代驾完成
  define('PUSH_CHA_JOU_FINISHED', 28);
}

if (!defined('PUSH_CHA_JOU_PAS_CANCELED')) {// 长途代驾乘客取消
  define('PUSH_CHA_JOU_PAS_CANCELED', 29);
}

if (!defined('PUSH_CHA_JOU_DIR_CANCELED')) {// 长途代驾司机取消
  define('PUSH_CHA_JOU_DIR_CANCELED', 30);
}


if (!defined('PUSH_RECHARGE_SUCCESS')) { // 充值成功
  define('PUSH_RECHARGE_SUCCESS', 31);
}

if (!defined('PUSH_PAYED_SUCCESS')) {// 行程支付成功
  define('PUSH_PAYED_SUCCESS', 32);
}

if (!defined('PUSH_WASH_PAYED')) {// 洗车支付成功
  define('PUSH_WASH_PAYED', 33);
}

if (!defined('PUSH_PRE_PAYED')) {// 预付款
  define('PUSH_PRE_PAYED', 34);
}

if (!defined('PUSH_INSURE_PAYED')) {// 保险支付
  define('PUSH_INSURE_PAYED', 35);
}

if (!defined('PUSH_CHA_JOU_DRIVER_SELECTED')) {// 乘客选择长途约车
  define('PUSH_CHA_JOU_DRIVER_SELECTED', 36);
}

if (!defined('PUSH_CHA_TRUCK_DRIVER_SELECTED')) {// 乘客选择货车
  define('PUSH_CHA_TRUCK_DRIVER_SELECTED', 37);
}

///////////////Push end////////////////


if (!defined('CHARGE_TYPE_RECHARGE')) {
  define('CHARGE_TYPE_RECHARGE', 0);
}

if (!defined('CHARGE_TYPE_TRIP_PAY')) {
  define('CHARGE_TYPE_TRIP_PAY', 1);
}

if (!defined('CHARGE_TYPE_PAY_PRE')) {
  define('CHARGE_TYPE_PAY_PRE', 2);
}

if (!defined('CHARGE_TYPE_PAY_WASH')) {
  define('CHARGE_TYPE_PAY_WASH', 3);
}

if (!defined('CHARGE_TYPE_INSURE')) {
  define('CHARGE_TYPE_INSURE', 4);
}

if (!defined('FIRST_TRADER_APPLY_LIMIT')) {// 最低可提交一级运营商积分总收益 (人民币单位分)
  define('FIRST_TRADER_APPLY_LIMIT', 1000000);
}

if (!defined('QUERY_DISTANCE')) {// 查询距离米
  define('QUERY_DISTANCE', 3000);
}

if (!defined('INSURE_REVIEWING')) { // 保险未审核
  define('INSURE_REVIEWING', 0);
}

if (!defined('INSURE_REVIEWED')) {// 保险已审核
  define('INSURE_REVIEWED', 1);
}

if (!defined('INSURE_PAYED')) {// 保险已付款
  define('INSURE_PAYED', 2);
}

if (!defined('INSURE_SENT')) { // 保险已发货
  define('INSURE_SENT', 3);
}

if (!defined('INSURE_DONE')) { // 保险已完成
  define('INSURE_DONE', 4);
}

if (!defined('PRE_PAY_RATE')) {
  define('PRE_PAY_RATE', .5);
}

if (!defined('CANCEL_PUNISHMENT_RATE')) {
  define('CANCEL_PUNISHMENT_RATE', .2);
}

if (!defined('REBATE_RATE')) {
    define('REBATE_RATE', .03);
}

if (!defined('PLATFORM_RATE')) {
    define('PLATFORM_RATE', .075);
}

if (!defined('LOCAL_TRIP_TIME_LIMIT')) {
    define('LOCAL_TRIP_TIME_LIMIT', 2);
}

if (!defined('WITHDRAW_LIMIT')) {
    define('WITHDRAW_LIMIT', 5000);
}

if (!defined('WITHDRAW_LIMIT_1')) { //每次最少提现金额
  define('WITHDRAW_LIMIT_1', 10000);
}

if (!defined('PUSH_DEVICE_OFFLINE')) {
    define('PUSH_DEVICE_OFFLINE', 0x1001);
}