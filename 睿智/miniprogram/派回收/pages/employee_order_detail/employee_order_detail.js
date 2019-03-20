
import { Core } from '../../extend/core.js';
var core = new Core();

Page({

  data: {
    hostName: core.baseUrl,
    input_false:false,
    mengShow: false
  },

  
  onLoad: function (e) {
    let that = this;
    that.setData({
      id:e.id
    })
  },

  onShow: function () {
    let that = this;
    that.requestOrderInfo()
  },

  //请求订单详情
  requestOrderInfo: function () {
    let that = this;
    core.request({
      url: 'api/service/order_datail',
      method: 'GET',
      data: {
        id: that.data.id
      },
      success: function (res) {
        var order = res.data.order;
        if (order.distance >= 1000) {
          order.distance = (order.distance / 1000).toFixed(1) + 'km';
        } else {
          order.distance = order.distance + 'm';
        }
        order.order_time = that.formatTime(order.order_time);
        order.dispatch_time = that.formatTime(order.dispatch_time);
        order.comment_time = that.formatTime(order.comment_time);
        order.materials_picurl = order.materials_picurl.split(",");
        if (order.status > 3){
          order.materials_count = order.materials_count.split(",");
        }
        order.star = (order.manner_star + order.speed_star)/2;
        that.setData({
          order: order
        });
      },
      fail: function (res) {
      }
    });
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

  //联系顾客
  callPhone: function (e) {
    wx.makePhoneCall({
      phoneNumber: e.target.dataset.contact
    })
  },

  //预览图片
  previewImg:function(e){
    let that =this;
    var index = e.currentTarget.dataset.index;
    var imgArr = that.data.order.materials_picurl;
    for (let i = 0; i < imgArr.length;i++){
      imgArr[i] = core.baseUrl + imgArr[i]
    }
    wx.previewImage({
      current: imgArr[index],  
      urls: imgArr
    })
  },


  //金额提交
  moneySubmit:function(e){
    let that = this;
    var input=[];
    var price;
    for (let key in e.detail.value) {
      input.push(e.detail.value[key])
    }
    if(that.data.order.is_free == 0){
       price = input.pop();
    }
    
    core.request({
      url: 'api/service/verify',
      method: 'POST',
      data: {
        id: that.data.id,
        materials_count:input.toString(),
        order_amount: price
      },
      success: function (res) {
        wx.showToast({
          title: res.msg,
        });
        setTimeout(function(){
          that.requestOrderInfo();
        },1000);
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        })
      }
    });
  
  },

  //删除订单
  deleteOrder: function () {
    let that = this;
    wx.showModal({
      content: '确认删除订单',
      success: function (res) {
        if (res.confirm) {

          core.request({
            url: 'api/service/delete',
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

  //减类型
  subtractType: function(e){
    let that = this;
    wx.showModal({
      content: '确认删除此类型？',
      success: function (res) {
        if (res.confirm) {

          var id = e.target.dataset.id;
          var materials_type = that.data.order.materials_type.split(',');
          for (let i = 0; i < materials_type.length; i++) {
            if (materials_type[i] == id) {
              materials_type.splice(i, 1);
            }
          }
          console.log(materials_type);
          that.updataType(materials_type);
        }
      }

    });
  },

  //弹出确认
  updataPop:function(){
    let that = this;
    that.updataType(that.data.wastetype_selected,true);
  },

  //更改类型
  updataType: function (materials_type,plus){
    let that = this;
    core.request({
      url: 'api/service/edit',
      method: 'POST',
      data: {
        id: that.data.id,
        materials_type: materials_type.toString()
      },
      success: function (res) {
        if (plus) {
          that.setData({
            mengShow: false
          });
        }
        that.requestOrderInfo();
        
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon:'none'
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
          for (let j = 0; j < waste_type.length;j++){
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