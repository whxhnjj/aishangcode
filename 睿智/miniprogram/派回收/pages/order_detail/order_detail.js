
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  data: {
    hostName: core.baseUrl,
    mengShow: false
  },


  onLoad: function (e) {
    let that =this;
    that.setData({
      id:e.id
    });
  },

  onShow: function () {
    let that =this;
    that.requestOrderInfo()
  
  },

  //请求订单详情
  requestOrderInfo:function(){
    let that =this;
    core.request({
      url: 'api/customer/order_detail',
      method: 'GET',
      data:{
        id:that.data.id
      },
      success: function (res) {
        var order = res.data.order;
        order.order_time = that.formatTime(order.order_time);
        order.receive_time = that.formatTime(order.receive_time);
        order.confirm_time = that.formatTime(order.confirm_time);
        order.comment_time = that.formatTime(order.comment_time);
        order.abort_time = that.formatTime(order.abort_time);
        order.abort_ok_time = that.formatTime(order.abort_ok_time);
        order.materials_picurl = order.materials_picurl.split(",");
        if (order.status > 3) {
          order.materials_count = order.materials_count.split(",");
        }
        order.star = (order.manner_star + order.speed_star) / 2;
        that.setData({
          order: order
        });
      },
      fail: function (res) {
      }
    })
  },

  //联系收纸员
  callCollector: function(e){
    let that = this;
    wx.makePhoneCall({
      phoneNumber: e.target.dataset.contact
    })
  },

  //格式化时间
  formatTime: function (v) {
    let that = this;
    var v = parseInt(v) * 1000;
    if (v < 0) { v = 0; }
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

  //取消订单
  removeOrder:function(){
    let that = this;
    wx.showModal({
      content: '确认取消订单吗？',
      success: function (res) {
        if (res.confirm) {

          core.request({
            url: 'api/customer/cancel',
            method: 'POST',
            data: {
              id: that.data.id
            },
            success: function (res) {
              wx.showToast({
                title: '订单取消成功'
              });
              setTimeout(function(){
                wx.switchTab({
                  url: '../index/index'
                })
              },1000)

            },
            fail: function (res) {
              wx.showToast({
                title: res.msg,
                icon: "none"
              });
            }
          });
        }

      }
    })
  },

  //用户确认收款
  userConfirm:function(){
    let that = this;
    wx.showModal({
      content: '金额是否正确',
      success: function (res) {
        if (res.confirm) {

          core.request({
            url: 'api/customer/confirm',
            method: 'POST',
            data: {
              id: that.data.id
            },
            success: function (res) {
              wx.showToast({
                title: '确认收款成功',
              });
              setTimeout(function(){
                that.requestOrderInfo();
              },1000)
            },
            fail: function (res) {
            }
          });

        }
      }

    })

  },

  //删除订单
  deleteOrder:function(){
    let that = this;
    wx.showModal({
      content: '确认删除订单',
      success: function (res) {
        if (res.confirm) {

          core.request({
            url: 'api/customer/delete',
            method: 'POST',
            data: {
              id: that.data.id
            },
            success: function (res) {
              wx.showToast({
                title: '删除成功',
              });
              setTimeout(function () {
                wx.navigateBack({
                  delta: 1
                });
              }, 1000)
            },
            fail: function (res) {
            }
          });

        }
      }

    });
  },

  //用户评价
  userComment:function(){
    let that =this;
    wx.navigateTo({
      url: '../evaluate/evaluate?id=' + that.data.id + '&realname=' + that.data.order.service_info.realname + '&avatarurl=' + that.data.order.service_info.avatarurl
    })

  },

  //预览图片
  previewImg: function (e) {
    let that = this;
    console.log(e.currentTarget.dataset.index);
    var index = e.currentTarget.dataset.index;
    var imgArr = that.data.order.materials_picurl;
    for (let i = 0; i < imgArr.length; i++) {
      imgArr[i] = core.baseUrl + imgArr[i]
    }
    wx.previewImage({
      current: imgArr[index],
      urls: imgArr
    })
  },

  //弹出确认
  updataPop: function () {
    let that = this;
    that.updataType(that.data.wastetype_selected);
  },

  //更改类型
  updataType: function (materials_type) {
    let that = this;
    core.request({
      url: 'api/customer/edit',
      method: 'POST',
      data: {
        id: that.data.id,
        materials_type: materials_type.toString()
      },
      success: function (res) {
        that.setData({
          mengShow: false
        });
        that.requestOrderInfo();
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });

  },

  //弹出层显示
  showPop: function () {
    let that = this;
    that.setData({
      mengShow: true
    });
    that.requestWasteType();
  },

  //弹出层隐藏
  outbtn: function () {
    let that = this;
    that.setData({
      mengShow: false
    });
  },

  //阻止冒泡事件
  inbtn: function (e) {
  },

  //获取废品类型
  requestWasteType: function () {
    let that = this;
    core.request({
      url: 'api/complete/price',
      method: 'GET',
      success: function (res) {
        var waste_type = res.data.price;
        var materials_type = that.data.order.materials_type.split(',');
        var wastetype_selected = [];
        for (let i = 0; i < materials_type.length; i++) {
          for (let j = 0; j < waste_type.length; j++) {
            if (materials_type[i] == waste_type[j].id) {
              waste_type[j].checked = true;
              wastetype_selected.push(materials_type[i]);
            }
          }
        }
        that.setData({
          waste_type: waste_type,
          wastetype_selected: wastetype_selected
        });
      },
      fail: function (res) {

      }
    });
  },

  //废品类型选择
  checkboxChange: function (e) {
    let that = this;
    that.setData({
      wastetype_selected: e.detail.value
    });
  }
})