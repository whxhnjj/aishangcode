

import { Core } from '../../extend/core.js';
var core = new Core();


Page({


  data: {
    hostName: core.baseUrl,
    nav_state: [true,false,false,false,false],
    original:[],
    state:0,
    page:1,
    order:[],
    isReachBottom: 1,
    load_more: '加载更多'
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page:1,
      original: []
    })
    that.requetOrder(that.data.original,that.data.state,that.data.page);
    wx.stopPullDownRefresh()
  },

  //监听用户上拉触底
  onReachBottom: function () {
    let that = this;
    if (that.data.isReachBottom) {
      that.setData({
        original: that.data.order
      })
      that.requetOrder(that.data.original, that.data.state, that.data.page);
    }
  },

  requetOrder: function (original,state,page){
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
  getOrder: function(e){
    let that =this;
    var state;
    if(!e){
      state = that.data.state;
    }else{
      state = e.target.dataset.state;
    }
    that.upgradeSelected(state);
    that.requetOrder(that.data.original, that.data.state, that.data.page);
  },

  upgradeSelected: function(s){
    let that = this;
    var nav_state = that.data.nav_state;
    for (var i = 0; i < nav_state.length;i++){
      nav_state[i] = false;
    }
    nav_state[s] = true;
    that.setData({
      nav_state: nav_state,
      state:s,
      page:1,
      original:[]
    })
  },


  onLoad: function (e) {
    let that = this;
    if (!e.state) {
      e.state = 0;
    }
  
    that.setData({ state: e.state})
    
  },
  
  onShow: function() {
    let that = this;
    that.setData({ page: 1, original:[] })
    that.requetOrder(that.data.original, that.data.state, that.data.page);
    that.upgradeSelected(that.data.state);
  },

  //格式化时间
  formatTime: function(t) {
    let that = this;
    var now_time = new Date().getTime();
    var end_time = new Date(t).getTime();
    var time = parseInt((end_time - now_time)/1000);
    var m = Math.floor(time / 60 % 60);
    if (m < 10) {
      m = '0' + m;
    }
    var s = time % 60;
    if (s < 10) {
      s = '0' + s;
    } 
    if(time>0){
      return m + '分' + s + '秒';
    }else{
      return '0秒';
    } 
  },

  //确认付款
  gotoBuyOrder: function(e){
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
  cancelOrder: function(e){
    let that = this;
    wx.showModal({
      title: '是否确认取消订单',
      success:function(res){
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
  deleteOrder: function(e){
    let that = this;
    wx.showModal({
      title: '是否确认删除订单',
      success: function(res){
        if(res.confirm){
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
  refundApply: function(e){
    let that = this;
    wx.showModal({
      title: '是否确认申请退款',
      success: function(res){
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
  takeDelivery: function(e){
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
  returnGoods: function(e){
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
  copyTradeSn: function(e){
    wx.setClipboardData({
      data: e.target.dataset.trade_sn,
      success: function (res) {
       wx.showToast({
         title: '复制成功',
         icon:'success'
       })
      }
    })
  }
})