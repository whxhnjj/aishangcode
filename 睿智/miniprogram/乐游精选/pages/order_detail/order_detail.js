
import { Core } from '../../extend/core.js';
var core = new Core();

const app = getApp();
const util = require('../../utils/util.js')
var timerTask;

var WxParse = require('../../extend/wxParse/wxParse.js');

Page({

  data: {
    hostName: core.baseUrl,
    code_state: false
  },

  onLoad: function (e) {
    let that = this;
    that.setData({
      isIphone: app.globalData.isIphone,
      id: e.id
    });
  },


  onShow: function () {
    let that = this;
    that.getOrderDetail();
  },

  //页面卸载
  onUnload: function () {
    clearTimeout(timerTask);
  },

  // 获取订单信息
  getOrderDetail: function(){
    let that = this;
    core.request({
      url: 'api/order/details',
      method: 'GET',
      data: {
        id: that.data.id,
      },
      success: function (res) {
        var order = res.data.order;

        order.goods_info.price = (order.goods_info.price/100).toFixed(2);
        order.goods_info.market_price = (order.goods_info.market_price / 100).toFixed(2);
        order.goods_price = (order.goods_price / 100).toFixed(2);
        order.cash_amount = (order.cash_amount / 100).toFixed(2);

        order.goods_info.goods_pic = order.goods_info.goods_pic.split(',');

        order.order_time = util.formatTime(order.order_time);
        order.close_time = util.formatTime(order.close_time);
        order.pay_time = util.formatTime(order.pay_time);
        order.int_part_pay_time = util.formatTime(order.int_part_pay_time);
        order.cancel_time = util.formatTime(order.cancel_time);
        order.send_time = util.formatTime(order.send_time);
        order.verify_time = util.formatTime(order.verify_time);
        order.comment_time = util.formatTime(order.comment_time);
        order.refund_time = util.formatTime(order.refund_time);

        // WxParse.wxParse('usage_rule', 'html', order.goods_info.usage_rule, that, 5);
        // WxParse.wxParse('statements', 'html', order.goods_info.statements, that, 5);

        if ( order.verify_code ){
          for (let i = 0; i < order.verify_code.length; i++) {
            order.verify_code[i].code = order.verify_code[i].code.toString().replace(/(.{4})/g, '$1 ');
          }
        }
        
          
        that.setData({
          order:order
        });

        if (order.status == 0 || order.status == 2) {
          that.lastTime(that.data.order.abort_time);
        }
      },
      fail: function (res) {
      }
    });
  },

  //剩余支付时间
  lastTime: function (t) {
    let that = this;
    var abort_time = new Date(t).getTime();
    var now_time = new Date().getTime();
    var time = parseInt((abort_time - now_time) / 1000);
    var m = Math.floor(time / 60 % 60);
    if (m < 10) {
      m = '0' + m;
    }
    var s = time % 60;
    if (s < 10) {
      s = '0' + s;
    }
    if (time == 0) {
      that.closeOrder();
    } else {
      // console.log(m + ':' + s)
      that.setData({
        end_time: '剩余支付时间' + m + '分' + s + '秒',
      })
      timerTask = setTimeout(function () {
        that.lastTime(t)
      }, 1000)
    }
  },

  //倒计时结束关闭订单
  closeOrder: function () {
    let that = this;
    core.request({
      url: 'api/order/close',
      data: {
        id: that.data.id
      },
      method: 'GET',
      success: function (res) {
        that.getOrderDetail();
        that.setData({
          end_time: ''
        })
      },
      fail: function (res) { }
    });
  },

  //拨打电话
  callPhone: function (e) {
    wx.makePhoneCall({
      phoneNumber: e.target.dataset.contacts
    });
  },

  //打开地图
  openMap: function (e) {
    var latlng = e.target.dataset.latlng.split(',');
    wx.openLocation({
      latitude: Number(latlng[0]),
      longitude: Number(latlng[1]),
      scale: 28,
      name: e.target.dataset.name,
      address: e.target.dataset.address
    })
  },

  // 取消订单
  cancelOrder: function(){
    let that = this;
    wx.showModal({
      title: '提示',
      content: '确认取消订单？',
      success: function (res) {
        if (res.confirm) {

          core.request({
            url: 'api/order/force',
            method: 'GET',
            data: {
              id: that.data.id,
            },
            success: function (res) {
              wx.showToast({
                title: res.msg,
              });
              setTimeout(function () {
                wx.navigateBack({
                  delta: 1,
                });
              }, 1000);
            }
          });

        }
      }
    });
  },

  // 删除订单
  deleteOrder: function () {
    let that = this;
    wx.showModal({
      title: '提示',
      content: '确认删除订单？',
      success:function(res){
        if (res.confirm){

          core.request({
            url: 'api/order/delete',
            method: 'GET',
            data: {
              id: that.data.id,
            },
            success: function (res) {
              wx.showToast({
                title: res.msg,
              });
              setTimeout(function(){
                wx.navigateBack({
                  delta: 1,
                });
              },1000);
            }
          });

        }
      }
    });
  },

  //二次支付
  payOrder:function(){
    let that = this;
    core.request({
      url: 'api/order/pay',
      method: 'GET',
      data: {
        id: that.data.id,
      },
      success: function (res) {
        wx.requestPayment({
          timeStamp: res.data.pay.timeStamp,
          nonceStr: res.data.pay.nonceStr,
          package: res.data.pay.package,
          signType: res.data.pay.signType,
          paySign: res.data.pay.paySign,
          success: function () {
            clearTimeout(timerTask);
            that.getOrderDetail();
          }
        });
      }
    });
  },

  //确认收货
  receiveOrder:function(){
    let that = this;
    wx.showModal({
      title: '提示',
      content: '是否确认收货？',
      success: function (res) {
        if (res.confirm) {

          core.request({
            url: 'api/order/receive',
            method: 'GET',
            data: {
              id: that.data.id,
            },
            success: function (res) {
              wx.showToast({
                title: res.msg,
              });
              setTimeout(function () {
                that.getOrderDetail();
              }, 1000);
            }
          });

        }
      }
    });
  },

  //兑换码展开隐藏
  codeState: function(){
    let that = this;
    that.setData({
      code_state: that.data.code_state ? false : true
    });
  }

})