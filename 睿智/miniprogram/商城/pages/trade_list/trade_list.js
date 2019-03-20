

import { Core } from '../../extend/core.js';
var core = new Core();


Page({

  data: {
    hostName: core.baseUrl,
    trade_type: [
      { id: 0, name: '全部' },
      { id: 1, name: '待付款' },
      { id: 2, name: '待发货' },
      { id: 3, name: '待收货' },
      { id: 4, name: '已收货' },
      { id: 5, name: '退换/售后' },
      { id: 6, name: '已完成' },
    ],
    original: [],
    index: 0,
    page: 1,
    order: [],
    isReachBottom: 1,
    load_more: '加载更多',
    scroll_left: 0
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      original: []
    })
    that.getTradeList(that.data.original, that.data.trade_type[that.data.index].id, that.data.page);
    wx.stopPullDownRefresh()
  },

  //监听用户上拉触底
  onReachBottom: function () {
    let that = this;
    if (that.data.isReachBottom) {
      that.setData({
        original: that.data.order
      })
      that.getTradeList(that.data.original, that.data.trade_type[that.data.index].id, that.data.page);
    }
  },

  onLoad: function (e) {
    let that = this;
    that.setData({
      page: 1,
      original: [],
      index: 0
    });
    that.getTradeType(e.state);
  },

  // 请求订单分类
  getTradeType: function (a) {
    let that = this;
    var trade_type = that.data.trade_type;
    for (let i = 0; i < trade_type.length; i++) {
      if (trade_type[i].id == a) {
        trade_type[i].selected = true;
      } else {
        trade_type[i].selected = false;
      }
    }
    that.setData({ trade_type: trade_type });
    that.getTradeList(that.data.original, a, that.data.page);
  },

  //请求订列表
  getTradeList: function (original, state, page) {
    let that = this;
    core.request({
      url: 'api/trade/lists',
      data: {
        state: state,
        page: page
      },
      method: 'GET',
      success: function (res) {
        console.log(res)

        if (page == 1) {
          that.setData({
            order: res.data.trade,
            isReachBottom: 1,
          })
        }

        if (res.data.trade.length == 0) {
          that.setData({ isReachBottom: 0, load_more: '已到达我的底线' });
        } else {

          var n = original;
          var order = res.data.trade;
          for (var i = 0; i < order.length; i++) {
            for (var j = 0; j < order[i].order.length; j++) {
              order[i].order[j].spec_value = JSON.parse(order[i].order[j].spec_value).toString();
            }
            if (order[i].status == 0) {
              order[i].abort_time = that.formatTime(order[i].abort_time);
            }
            n.push(order[i])
          }

          that.setData({
            order: n,
          });

          page++;

          that.setData({
            page: page
          })
        }


      },
      fail: function (res) {

      }
    });
  },


  //更新订单分类
  updataTradeList: function (e) {
    let that = this;
    var index = e.currentTarget.dataset.index;
    var id = e.currentTarget.dataset.id;
    var trade_type = that.data.trade_type;

    for (let i = 0; i < trade_type.length; i++) {
      trade_type[i].selected = false;
    }
    trade_type[index].selected = true;

    //居中偏移
    var query = wx.createSelectorQuery();
    query.select('.item_' + index).boundingClientRect(function (rect) {
      var offset_left = e.currentTarget.offsetLeft;
      var scroll_left = offset_left - (375 - rect.width) / 2 + 15;
      that.setData({
        scroll_left: scroll_left
      });
    }).exec();;


    that.setData({
      page: 1,
      original: [],
      trade_type: trade_type,
      index: index
    });

    that.getTradeList(that.data.original, that.data.trade_type[that.data.index].id, that.data.page);

  },

  //格式化时间
  formatTime: function (t) {
    let that = this;
    var now_time = new Date().getTime();
    var end_time = new Date(t).getTime();
    var time = parseInt((end_time - now_time) / 1000);
    var m = Math.floor(time / 60 % 60);
    if (m < 10) {
      m = '0' + m;
    }
    var s = time % 60;
    if (s < 10) {
      s = '0' + s;
    }
    if (time > 0) {
      return m + '分' + s + '秒';
    } else {
      return '0秒';
    }
  },

  //确认付款
  gotoBuyOrder: function (e) {
    let that = this;
    core.request({
      url: 'api/trade/pay',
      data: {
        id: e.target.dataset.id
      },
      method: 'POST',
      success: function (res) {
        wx.requestPayment({
          'timeStamp': res.data.pay.timeStamp,
          'nonceStr': res.data.pay.nonceStr,
          'package': res.data.pay.package,
          'signType': res.data.pay.signType,
          'paySign': res.data.pay.paySign,
          'success': function (res) {
            that.getOrder();
          }
        })

      },
      fail: function (res) {

      }
    });

  },
  //取消订单
  cancelOrder: function (e) {
    let that = this;
    wx.showModal({
      title: '是否确认取消订单',
      success: function (res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/force',
            data: {
              id: e.target.dataset.id
            },
            method: 'POST',
            success: function (res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function () {
                  setTimeout(function () { that.getOrder(); }, 1500)
                }
              })
            },
            fail: function (res) {

            }
          });
        }
      },
    })

  },
  //删除订单
  deleteOrder: function (e) {
    let that = this;
    wx.showModal({
      title: '是否确认删除订单',
      success: function (res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/delete',
            data: {
              id: e.target.dataset.id
            },
            method: 'POST',
            success: function (res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function () {
                  setTimeout(function () { that.getOrder(); }, 1500)
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
  //申请退款
  refundApply: function (e) {
    let that = this;
    wx.showModal({
      title: '是否确认申请退款',
      success: function (res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/refund',
            data: {
              id: e.target.dataset.id
            },
            method: 'POST',
            success: function (res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function () {
                  setTimeout(function () { that.getOrder(); }, 1500)
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
  //确认收货
  takeDelivery: function (e) {
    let that = this;
    wx.showModal({
      title: '是否确认收货',
      success: function (res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/receive',
            data: {
              id: e.target.dataset.id
            },
            method: 'POST',
            success: function (res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function () {
                  setTimeout(function () { that.getOrder(); }, 1500)
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
  //申请退货
  returnGoods: function (e) {
    let that = this;
    wx.showModal({
      title: '是否确认申请退货',
      success: function (res) {
        if (res.confirm) {
          core.request({
            url: 'api/trade/refund_trade',
            data: {
              id: e.target.dataset.id
            },
            method: 'POST',
            success: function (res) {
              wx.showToast({
                title: res.msg,
                icon: 'success',
                duration: 2000,
                success: function () {
                  setTimeout(function () { that.getOrder(); }, 1500)
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
  //复制订单编号
  copyTradeSn: function (e) {
    wx.setClipboardData({
      data: e.target.dataset.trade_sn,
      success: function (res) {
        wx.showToast({
          title: '复制成功',
          icon: 'success'
        })
      }
    })
  }
})