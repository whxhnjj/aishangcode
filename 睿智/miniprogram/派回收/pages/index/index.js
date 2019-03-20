
import { Core } from '../../extend/core.js';
var core = new Core();

const app = getApp();

Page({

  data:{
    hostName: core.baseUrl,
    state:1,
    order:[],
    new_order:0,
    wait_order:0,
    pop_state:false,
    remove_state:false,
    pop_id:-1,
    page: 1,
    isReachBottom: 1,
    hasMoreData: [],
    order_text:'暂无更多任务',
    swiper: []
  },

  onLoad: function(){
    let that = this;
    that.setData({
      member_rule: app.globalData.member_rule
    });
    if (app.globalData.member_rule == 0){
      that.getSwiper();
    }
  },

  onShow:function(){
    let that = this;
    if (that.data.member_rule != 0 ){
      that.setData({ page: 1, hasMoreData: [] });
      that.getTaskList(that.data.state, that.data.page, that.data.hasMoreData);
      that.getOrderNum();
    }
  },

  //首页轮播
  getSwiper: function(){
    let that = this;
    core.request({
      url: 'api/complete/swiper',
      method: 'GET',
      success: function (res) {
        that.setData({
          swiper: res.data.swiper
        });
      },
      fail: function (res) {
      }
    });
  },

  linkWebView: function(e){
    let that = this;
    if (e.target.dataset.url){
      wx.navigateTo({
        url: '../webview/webview?url=' + e.target.dataset.url
      });
    }
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    if (that.data.member_rule != 0) {
    that.setData({
      page:1,
      hasMoreData:[],
      order_text: '正在加载'
    })
    that.getTaskList(that.data.state, that.data.page, that.data.hasMoreData);
    }
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (that.data.isReachBottom) {
      that.setData({
        hasMoreData: that.data.order
      })
      that.getTaskList(that.data.state, that.data.page, that.data.hasMoreData);
    }
  },

  //获取订单数量
  getOrderNum: function () {
    let that = this;
    core.request({
      url: 'api/service/counts',
      method: 'GET',
      success: function (res) {
        that.setData({
          new_order: res.data.count.wait_receive,
          wait_order: res.data.count.wait_verify,
        })
      },
      fail: function (res) {
      }
    });
  },

  //收纸员任务状态切换
  changeState:function(e){
    let that = this;
    that.setData({
      state: e.target.dataset.state,
      page:1,
      hasMoreData:[],
      order_text: '暂无更多任务'
    })
    that.getTaskList(that.data.state, that.data.page, that.data.hasMoreData);
  },

  //收纸员请求任务列表
  getTaskList: function (state, page, hasMoreData){
    let that = this;
    core.request({
      url: 'api/service/order_list',
      method: 'GET',
      data:{
        state: state,
        page:page,
        limit: 5
      },
      success: function (res) {

        if (page == 1) {
          that.setData({
            order: res.data.order,
            isReachBottom: 1,
          })
        }

        if (res.data.order.length < 5) {

          var n = hasMoreData;
          var order = res.data.order;
          for (var i = 0; i < order.length; i++) {
            if (order[i].distance >= 1000) {
              order[i].distance = (order[i].distance / 1000).toFixed(1) + 'km';
            } else {
              order[i].distance = order[i].distance + 'm';
            }
            n.push(order[i])
          }

          that.setData({
            isReachBottom: 0,
            order_text: '暂无更多任务',
            order: n
          });

        } else {

          var n = hasMoreData;
          var order = res.data.order;
          for (var i = 0; i < order.length; i++) {
            if (order[i].distance >= 1000) {
              order[i].distance = (order[i].distance / 1000).toFixed(1) + 'km';
            } else {
              order[i].distance = order[i].distance + 'm';
            }
            n.push(order[i]);
          }

          that.setData({
            order: n,
          });

          page++;

          that.setData({
            page: page,
            order_text: '加载更多'
          })
        }

      },
      fail: function (res) {

      }
    });
  },

  //收纸员接单
  orderTaking:function(e){
    let that = this;
    wx.showModal({
      content: '确认接单？',
      success: function(res){
        if (res.confirm) {

          core.request({
            url: 'api/service/receive',
            method: 'POST',
            data: {
              id: e.target.dataset.id
            },
            success: function (res) {
              wx.showToast({
                title: '接单成功',
                icon: 'success'
              });
              setTimeout(function () {
                wx.navigateTo({
                  url: '../employee_order_detail/employee_order_detail?id='+e.target.dataset.id
                });
              }, 1000);
            },
            fail: function (res) {
            }
          });
          
        }
      }
    })
  },

  //拒收弹出
  refuseOrder: function (e) {
    let that = this;
    that.setData({
      refuse_state:true,
      pop_id:e.target.dataset.id
    })
  },

  //收纸员拒收
  refuseSubmit:function(e){
    let that = this;
    core.request({
      url: 'api/service/refuse',
      method: 'POST',
      data: {
        id: e.detail.value.id,
        refuse_reason: e.detail.value.reason,
      },
      success: function (res) {
        that.setData({ pop_state:false})
        wx.showToast({
          title: '拒收成功',
          icon: 'success'
        });
        setTimeout(function () {
          wx.reLaunch({
            url: '../index/index'
          });
        }, 1000);
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });

  },

  //拒收取消
  refuseReset:function(){
    let that =this;
    that.setData({
      refuse_state: false
    })
  },

  //取消弹出
  removeOrder: function (e) {
    let that = this;
    that.setData({
      remove_state: true,
      pop_id: e.target.dataset.id
    })
  },

  //收纸员取消
  removeSubmit: function (e) {
    let that = this;
    core.request({
      url: 'api/service/abort',
      method: 'POST',
      data: {
        id: e.detail.value.id,
        abort_reason: e.detail.value.abort_reason,
      },
      success: function (res) {
        that.setData({ pop_state: false })
        wx.showToast({
          title: '取消成功',
          icon: 'success'
        });
        setTimeout(function () {
          wx.reLaunch({
            url: '../index/index'
          });
        }, 1000);
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });

  },

  //取消取消
  removeReset: function () {
    let that = this;
    that.setData({
      remove_state: false
    })
  },

  //联系顾客
  contactsCustomer:function(e){
    wx.makePhoneCall({
      phoneNumber: e.target.dataset.contact
    })
  },

  //打开地图
  openMap:function(e){
    var latlng = e.target.dataset.latlng.split(',');
    wx.openLocation({
      latitude: Number(latlng[0]),
      longitude: Number(latlng[1]),
      scale: 28,
      name: e.target.dataset.area,
      address: e.target.dataset.address
    })
  },

  //阻止冒泡
  inMap:function(e){},

  //用户发单
  newOrder: function () {
    let that = this;
    if (app.globalData.is_complete == 0) {
      wx.navigateTo({
        url: '../register/register',
      });
    } else {
      wx.navigateTo({
        url: '../new_order/new_order',
      });
    }
  },

  //公益捐赠
  newFree: function () {
    let that = this;
    if (app.globalData.is_complete == 0) {
      wx.navigateTo({
        url: '../register/register',
      });
    } else {
      wx.navigateTo({
        url: '../new_order/new_order?is_free=1',
      });
    }
  },

  //formid
  formidSubmit: function (e) {
    let that = this;
    console.log(e.detail.formId)
    if (e.detail.formId && e.detail.formId != 'the formId is a mock one') {

      core.request({
        url: 'api/complete/formid',
        method: 'POST',
        show_loading: 'no',
        data: {
          formid: e.detail.formId
        },
        success: function (res) {
        },
        fail: function (res) {
        }
      });
    }
  },

  
  onShareAppMessage: function () {
  }
})
