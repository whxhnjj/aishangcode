
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  
  data: {
    page: 1,
    isReachBottom: 1,
    hasMoreData: [],
    order: [],
    order_text: '暂无更多订单',
    refuse_state: false,
    remove_state: false,
    pop_id: -1,
    state:0
  },

  onLoad: function (e) {
    let that =this;
    if(e.state){
      that.setData({
        state: e.state
      });
    }
  },

  onShow: function () {
    let that = this;
    that.setData({ page: 1, hasMoreData:[]});
    that.getOrderList(that.data.state, that.data.page, that.data.hasMoreData);
  },

  //下拉刷新
  onPullDownRefresh: function () {
    let that = this;
    that.setData({
      page: 1,
      hasMoreData: [],
      order_text: '正在加载'
    })
    that.getOrderList(that.data.state, that.data.page, that.data.hasMoreData);
    wx.stopPullDownRefresh();
  },

  //触底事件
  onReachBottom: function () {
    let that = this;
    if (that.data.isReachBottom) {
      that.setData({
        hasMoreData: that.data.order
      })
      that.getOrderList(that.data.state, that.data.page, that.data.hasMoreData);
    }
  },

  //切换订单
  changeOrderList: function (e) {
    let that = this;
    that.setData({
      state: e.target.dataset.state,
      page: 1,
      hasMoreData: [],
      order_text: '暂无更多订单'
    })
    that.getOrderList(that.data.state, that.data.page, that.data.hasMoreData);
  },

  //收纸员请求任务列表
  getOrderList: function (state, page, hasMoreData) {
    let that = this;
    core.request({
      url: 'api/service/order_list',
      method: 'GET',
      data: {
        state: state,
        page: page,
        limit: 5
      },
      success: function (res) {

        if (page == 1) {
          that.setData({
            order: res.data.order,
            isReachBottom: 1,
          })
        }

        if (res.data.order.length <5) {

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
            n.push(order[i])
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

  //联系顾客
  contactsCustomer: function (e) {
    wx.makePhoneCall({
      phoneNumber: e.target.dataset.contact
    })
  },

  //打开地图
  openMap: function (e) {
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
  inMap: function (e) { },

  //收纸员接单
  orderTaking: function (e) {
    let that = this;
    wx.showModal({
      content: '确认接单？',
      success: function (res) {
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
                  url: '../employee_order_detail/employee_order_detail?id=' + e.target.dataset.id
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
      refuse_state: true,
      pop_id: e.target.dataset.id
    })
  },

  //收纸员拒收
  refuseSubmit: function (e) {
    let that = this;
    core.request({
      url: 'api/service/refuse',
      method: 'POST',
      data: {
        id: e.detail.value.id,
        refuse_reason: e.detail.value.reason,
      },
      success: function (res) {
        that.setData({ pop_state: false })
        wx.showToast({
          title: '拒收成功',
          icon: 'success'
        });
        setTimeout(function () {
          that.setData({ page: 1, hasMoreData: [], refuse_state:false});
          that.getOrderList(that.data.state, that.data.page, that.data.hasMoreData);
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
  refuseReset: function () {
    let that = this;
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
          that.setData({ page: 1, hasMoreData: [], remove_state: false });
          that.getOrderList(that.data.state, that.data.page, that.data.hasMoreData);
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

  //formid
  formidSubmit: function (e) {
    let that = this;
    console.log(e.detail.formId)
    if (e.detail.formId && e.detail.formId != 'the formId is a mock one') {

      core.request({
        url: 'api/complete/formid',
        method: 'POST',
        data: {
          formid: e.detail.formId
        },
        success: function (res) {
        },
        fail: function (res) {
        }
      });
    }
  }
  
})