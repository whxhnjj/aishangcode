
import { Core } from '../../extend/core.js';
var core = new Core();

var app = getApp();

Page({

  data: {
    service_position:-375,
    service_data: '',
    service_text: "",
    timelimit_position:-375,
    timelimit_data:'',
    timelimit_text:"",
    hostName: core.baseUrl
  },

  onLoad: function (e) {
    let that = this;
    that.setData({
      id:e.id,
      realname: e.realname,
      avatarurl: e.avatarurl
    });
    that.getStar();
  },

  onShow: function () {
  
  },

  chooseStar: function(e){
    let that = this;
    var name = e.target.dataset.name;
    if(e.target.dataset.num){
      var num = e.target.dataset.num;

      if (name == 'service'){
        switch (parseInt(num)) {
          case 1: that.starPosition(name, 300, num, that.data.manner_star[1]); break;
          case 2: that.starPosition(name, 225, num, that.data.manner_star[2]); break;
          case 3: that.starPosition(name, 150, num, that.data.manner_star[3]); break;
          case 4: that.starPosition(name, 75, num, that.data.manner_star[4]); break;
          case 5: that.starPosition(name, 0, num, that.data.manner_star[5]); break;
        }
      }else{
        switch (parseInt(num)) {
          case 1: that.starPosition(name, 300, num, that.data.speed_star[1]); break;
          case 2: that.starPosition(name, 225, num, that.data.speed_star[2]); break;
          case 3: that.starPosition(name, 150, num, that.data.speed_star[3]); break;
          case 4: that.starPosition(name, 75, num, that.data.speed_star[4]); break;
          case 5: that.starPosition(name, 0, num, that.data.speed_star[5]); break;
        }
      }
      
    }
  },

  starPosition:function(name,x,num,text){
    let that = this;
    that.setData({
      [name + '_position']:-x,
      [name + '_data']:num,
      [name + '_text']: text,
    })
  },

  //评价提交
  commentSubmit:function(e){
    let that = this;
    core.request({
      url: 'api/customer/comment',
      method: 'POST',
      data: {
        id: that.data.id,
        manner_star: that.data.service_data,
        speed_star: that.data.timelimit_data,
        comment_content: e.detail.value.comment
      },
      success: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'success'
        });
        setTimeout(function () {
          wx.navigateBack({
            delta: 1
          });
        }, 1000);
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon:"none"
        });
      }
    });
  },

  //星级描述获取
  getStar: function (e) {
    let that = this;
    core.request({
      url: 'api/core/star',
      method: 'GET',
      success: function (res) {
        that.setData({
          manner_star: res.data.star.manner_star,
          speed_star: res.data.star.speed_star,
        });
      },
      fail: function (res) {
    
      }
    });
  }
})