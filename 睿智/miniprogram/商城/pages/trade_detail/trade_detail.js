

var app = getApp();
import {Core} from '../../extend/core.js';
var core = new Core();
var timerTask;

Page({

  data: {
    hostName: core.baseUrl,
  },

  onLoad: function(e) {
    let that = this;
    that.requestList(e);
    that.setData({
      isIphoneX: app.globalData.isIphoneX
    })
  },

  //请求数据
  requestList: function(e) {
    let that = this;
    var id = '';
    if (e) {
      id = e.id;
    } else {
      id = that.data.list.id
    }
    core.request({
      url: 'api/trade/details',
      data: {
        id: id
      },
      method: 'GET',
      success: function(res) {
        var list = res.data.trade;
        list.addr_info = JSON.parse(list.addr_info);
        for (var i = 0; i < list.order.length; i++) {
          list.order[i].spec_value = JSON.parse(list.order[i].spec_value).toString();
        }
        //格式化时间
        list.close_time = that.formatTime(list.close_time);
        list.pay_time = that.formatTime(list.pay_time);
        list.cancel_time = that.formatTime(list.cancel_time);
        list.cancel_pay_time = that.formatTime(list.cancel_pay_time);
        list.send_time = that.formatTime(list.send_time);
        list.receive_time = that.formatTime(list.receive_time);
        list.some_refund_time = that.formatTime(list.some_refund_time);
        list.some_refund_pay_time = that.formatTime(list.some_refund_pay_time);
        list.all_refund_time = that.formatTime(list.all_refund_time);
        list.all_refund_pay_time = that.formatTime(list.all_refund_pay_time);
        
        //格式化字符串 goods_info
        list.order[0].goods_info = JSON.parse(list.order[0].goods_info);

        console.log(list)


        that.setData({list: list})

        if (list.status == 0) {
          that.lastTime(that.data.list.abort_time);
        }
      },
      fail: function(res) {}
    });
  },

  //剩余支付时间
  lastTime: function(t) {
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
    if (time < 0) {
      that.closeOrder();
    } else {
      // console.log(m + ':' + s)
      that.setData({
        end_time: '剩余支付时间' + m + '分' + s + '秒',
      })
      timerTask = setTimeout(function() {
        that.lastTime(t)
      }, 1000)
    }
  },

  //倒计时结束关闭订单
  closeOrder: function() {
    let that = this;
    core.request({
      url: 'api/trade/close',
      data: {
        id: that.data.list.id
      },
      method: 'POST',
      success: function(res) {
        that.requestList();
        that.setData({
          end_time: '订单已关闭'
        })
      },
      fail: function(res) {}
    });


  },

  //确认支付
  submitOrder: function() {
    let that = this;
    core.request({
      url: 'api/trade/pay',
      data: {
        id: that.data.list.id
      },
      method: 'POST',
      success: function(res) {
        wx.requestPayment({
          'timeStamp': res.data.pay.timeStamp,
          'nonceStr': res.data.pay.nonceStr,
          'package': res.data.pay.package,
          'signType': res.data.pay.signType,
          'paySign': res.data.pay.paySign,
          'success': function(res) {
            that.requestList();
          }
        })

      },
      fail: function(res) {
        wx.showToast({
          title: res.msg,
          icon:'none'
        })
      }
    })
  },

  //取消订单
  cancelOrder: function() {
    let that = this;
    wx.showModal({
      title: '是否确认取消订单',
      success: function(res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/force',
            data: {
              id: that.data.list.id
            },
            method: 'POST',
            success: function(res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function() {
                  wx.navigateBack({
                    delta: 1
                  })
                }
              })
            },
            fail: function(res) {
              wx.showToast({
                title: res.msg,
                icon: 'none'
              })
            }
          });
        }
      },
    })

  },

  //删除订单
  deleteOrder: function() {
    let that = this;
    wx.showModal({
      title: '是否确认删除订单',
      success: function(res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/delete',
            data: {
              id: that.data.list.id
            },
            method: 'POST',
            success: function(res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function() {
                  wx.navigateBack({
                    delta: 1
                  })
                }
              })
            },
            fail: function(res) {

            }
          });

        }
      }
    })

  },

  //申请退款
  refundApply: function() {
    let that = this;
    wx.showModal({
      title: '是否确认申请退款',
      success: function(res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/refund',
            data: {
              id: that.data.list.id
            },
            method: 'POST',
            success: function(res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function() {
                  setTimeout(function() {
                    that.requestList();
                  }, 1500)
                }
              })
            },
            fail: function(res) {

            }
          });
        }
      }
    })

  },

  //确认收货
  takeDelivery: function() {
    let that = this;
    wx.showModal({
      title: '是否确认收货',
      success: function(res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/receive',
            data: {
              id: that.data.list.id
            },
            method: 'POST',
            success: function(res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function() {
                  setTimeout(function() {
                    that.requestList();
                  }, 1500)
                }
              })
            },
            fail: function(res) {

            }
          });
        }
      }
    })

  },

  //全部申请退货
  returnGoods: function() {
    let that = this;
    wx.showModal({
      title: '是否确认申请退货',
      success: function(res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/refund_trade',
            data: {
              id: that.data.list.id
            },
            method: 'POST',
            success: function(res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function() {
                  setTimeout(function() {
                    that.requestList();
                  }, 1500)
                }
              })
            },
            fail: function(res) {

            }
          });
        }
      }
    })


  },

  //部分申请退货
  someReturnGoods: function (e) {
    let that = this;
    var orderid = e.target.dataset.orderid;
    wx.showModal({
      title: '是否确认申请退货',
      success: function (res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/refund_order',
            data: {
              order_id: orderid
            },
            method: 'POST',
            success: function (res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function () {
                  setTimeout(function () {
                    that.requestList();
                  }, 1500)
                }
              })
            },
            fail: function (res) {

            }
          });
        }
      }
    })
  },

  //格式化时间
  formatTime: function(v){
    let that = this;
    var v = parseInt(v) * 1000;
    if(v < 0){ v=0;}
    var dateObj = new Date(v);
    var month = dateObj.getMonth() + 1;
    var day = dateObj.getDate();
    var hours = dateObj.getHours();
    var minutes = dateObj.getMinutes();
    var seconds = dateObj.getSeconds();
    if (month < 10) {
      month = "0" + month;
    }
    if (day < 10) {
      day = "0" + day;
    }
    if (hours < 10) {
      hours = "0" + hours;
    }
    if (minutes < 10) {
      minutes = "0" + minutes;
    }
    if (seconds < 10) {
      seconds = "0" + seconds;
    }
    var UnixTimeToDate = dateObj.getFullYear() + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
    return UnixTimeToDate; 
  },


  //页面卸载
  onUnload: function() {
    clearTimeout(timerTask);
  },


  //复制卖家留言
  copyMessage: function (e) {
    wx.setClipboardData({
      data: e.target.dataset.msg,
      success: function (res) {
        wx.showToast({
          title: '复制成功',
          icon: 'success'
        })
      }
    })
  }

})