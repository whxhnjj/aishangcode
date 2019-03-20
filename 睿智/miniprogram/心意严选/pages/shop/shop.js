

import { Core } from '../../extend/core.js';
var core = new Core();


Page({

  data: {
    hostName: core.baseUrl
  },

  /**生命周期函数--监听页面加载*/
  onLoad: function (e) {
    let that = this;
    that.getShopInfo();
  },

  
  customCall: function(){
    let that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.shop.custom_tel
    })
  },
  saleCall: function(){
    let that = this;
    wx.makePhoneCall({
      phoneNumber: that.data.shop.sale_tel
    })
  },
  openAddress: function(e){
    let that = this;
    var latlng = e.target.dataset.latlng.split(',');
    console.log(latlng)
    
    wx.openLocation({
      latitude: parseFloat(latlng[0]),
      longitude: parseFloat(latlng[1]),
      name: e.target.dataset.name,
      address: e.target.dataset.address,
      scale:14
    })

  },
  
  getShopInfo: function () {
    let that = this;
    core.request({
      url: 'api/shop/fetch',
      method: 'GET',
      success: function (res) {
        console.log(res)
        that.setData({
          shop:res.data.shop
        })
      },
      fail: function (res) {

      }
    })
  },




  //分享
  onShareAppMessage: function () {
  
  }
})