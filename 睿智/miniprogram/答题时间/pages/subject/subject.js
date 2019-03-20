// //subject.js
const app = getApp();
import NumberAnimate from "../../utils/NumberAnimate";

var times;
var exam_id;
var i = 0;
var j = 0;
var exam;
// canvas缩放比例
var w = 67.5;
var r = 65;
var z = 4;
var isfirst = true;
var stand_time ;
var score = 0 ;
const innerAudioContext = wx.createInnerAudioContext();

var start; //答题开始时间
var end; //答题结束时间
var total_time = 0; //答题总用时
var rights = 0 ; //答对题数
var wrongs = 0 ; //答错题数
var detail = [];
var countTimer =  null;// 设置 定时器 初始为null;
var fontTimer = null;
var fontTimer2 = null;
var event;


Page({
  data: {
    time: '',
    num: 0,
    options: [],
    _state: 0,
    score: 0, //答题总成绩
    total_time: 0,//总答题用时

    // 加载页面 答题页面 结果页面 （显示参数）
    load_display: "block",
    answer_display: "none",
    result_display:"none",
    error_display:"none",
    domain: app.globalData.domain,

    font_num:3,//加载倒计时数字
    font_state:"countdown_small",//加载倒计时样式
    viewstyle:[],
    record_id: 0
  },


  
 //绘制圆形
  //绘制背景 
  drawProgressbg: function () {
    // 使用 wx.createContext 获取绘图上下文 context
    var ctx = wx.createCanvasContext('canvasProgressbg')
    ctx.setLineWidth(z);// 设置圆环的宽度
    ctx.setStrokeStyle('#495d87'); // 设置圆环的颜色
    ctx.setLineCap('round') // 设置圆环端点的形状
    ctx.beginPath();//开始一个新的路径
    ctx.arc(w, w, r, 0, 2 * Math.PI, false);
    //设置一个原点(110,110)，半径为100的圆的路径到当前路径
    ctx.stroke();//对当前路径进行描边
    ctx.draw();
  },

  // 绘制圆
  drawCircle: function (step,color) {
    var context = wx.createCanvasContext('canvasProgress');
    context.setLineWidth(z);
    context.setStrokeStyle(color);
    context.setLineCap('round')
    context.beginPath();
    // 参数step 为绘制的圆环周长，从0到2为一周 。 -Math.PI / 2 将起始角设在12点钟位置 ，结束角 通过改变 step 的值确定
    context.arc(w, w, r, -Math.PI / 2, step * Math.PI - Math.PI / 2, false);
    context.stroke();
    context.draw()
  },

  // 定时器实现逐步绘制
  countInterval: function () {
    // 设置倒计时 定时器 每100毫秒执行一次，计数器num-1 ,耗时10秒绘一圈
    this.countTimer = setInterval(() => {
      if (this.data.num >= 0) {
        /*注意此处 传参 step 取值范围是0到2，
        所以 计数器 最大值 100 对应 2 做处理，计数器num=100的时候step=2
        */
        this.drawCircle(this.data.num / (stand_time / 2), 'rgb(0,255,187)')
        if ((/(^[1-9]\d*$)/.test(this.data.num / 10))) {
          // 当时间为整数秒的时候 改变时间
          this.setData({
            time: this.data.num / 10
          });        
        }
      } else {   
        this.setData({
          time: 0
        }); 
        this.drawCircle(2, 'red')
        if(exam.timer == 1 ){
          this.answerSub(0);
          innerAudioContext.src = "http://dati.webimage.cn/static/xcx/wrong.mp3";
          innerAudioContext.play();
        }
        
      }
      this.data.num--;
    }, 100)

  },

  // 加载页面倒计时
  numInterval: function (num) {
    let that = this;
    var num = 4;
    innerAudioContext.src = "http://dati.webimage.cn/static/xcx/readygo.mp3";
    innerAudioContext.play();
    this.fontTimer = setInterval(() => {
      console.log(num)
      if (num > 0) {
        that.setData({
          font_num: num,
          font_state: "countdown_small"
        })
        if (num != 1) {
          this.fontTimer2 = setTimeout(() =>{
            that.setData({
              font_state: "countdown_big"
            })
          }, 980)
        }
        num--
      } else {
        that.setData({
          load_display: "none",
          answer_display: "block",
          result_display: "none",
        })
        that.resetExam(0);
        clearInterval(this.fontTimer);
        clearTimeout(this.fontTimer2);
      }
    }, 1000)
    
  },


  


 //页面卸载

  onUnload: function(){
    
    let that = this;

    // if (that.data.pagenum < that.data.pagecount){
    //   wx.showModal({
    //     content: '退出后本次答题机会将作废，成绩已当前得分为准，是否确认后退',
    //     success: function(res){
    //       if(res.confirm){
    //         that.submit()
    //       }else if(res.cancel){
    //         console.log("用户点击取消")
    //         return false
    //       }
    //     }
    //   })
    // }

    if (that.data.pagenum < that.data.pagecount) {
        that.submit()
    }

    clearInterval(this.fontTimer);
    clearInterval(this.countTimer);
    clearTimeout(this.fontTimer2);
    clearTimeout(this.waitTimer);
    i = 0;
    rights = 0; //答对题数
    wrongs = 0;
    score = 0;
    total_time = 0;
    detail = [];
    that.setData({
      score: score,
      total_time: 0,
      load_display: "none",
      answer_display: "block",
      result_display: "none",
    })
    console.log(this.countTimer)
  },
 //页面加载 
  onLoad: function(e){
    let that = this;
    exam_id = e.exam_id
    times = e.times 
    console.log(e)
    event = e
    if (e.scene) { exam_id = e.scene }
    i = 0 //页面重新加载时，刷新题目，i重置为0
    rights = 0; //答对题数
    wrongs = 0;
    score = 0;
    total_time = 0;
    detail = [];
    that.setData({
      exam_id: exam_id,
      user: app.globalData.user,
      times: times,
      score: score,
      total_time: 0,
      _state: 0,
      load_display: "block",
      answer_display: "none",
      result_display: "none"
    })
    
    wx.getSystemInfo({     
      //获取系统信息成功，将系统窗口的宽高赋给页面的宽高
      success: function (res) {
        var widths = res.windowWidth;
          w = (widths / 750 ) * 67.5;
          r = (widths / 750) * 63.5;
          z = (widths / 750) * 6;
      }
    }),
    that.getData()
  },
  
  // 再答一次
  restart: function(){
    let that = this;
    that.onLoad(event);
  },
  
  // 答题
  answerSub: function(event){
    let that = this;
    clearInterval(this.countTimer);
    end = new Date().getTime();
    var use_time = end - start ;

    var option_id = (event == 0) ? 0 : event.currentTarget.dataset.id;
    var is_right = (event == 0) ? 0 : event.currentTarget.dataset.isright;
    var _index = (event == 0) ? -1 : event.currentTarget.dataset.index;

    
    if (is_right){
      var average_score = 100 / Number(this.data.pagecount);
      if (exam.timer == 0) {
        // var subject_score = Math.round(average_score * 100) / 100;
        var subject_score = average_score;
      }else{

        var residue_time = stand_time * 100 - use_time;
        if (exam.timer == 1 && residue_time < 0){
          var subject_score = 0;//超时判错
        }else{
          // var subject_score = Math.round((average_score + residue_time * 0.0001) * 100) / 100;
          var subject_score = average_score + residue_time * 0.0001;

        }
         
      } 
      innerAudioContext.src = "http://dati.webimage.cn/static/xcx/right.mp3";
      innerAudioContext.play();
      if( subject_score < 0 ) {subject_score = 0.01 };
      

      rights++;
    }else{
      innerAudioContext.src = "http://dati.webimage.cn/static/xcx/wrong.mp3";
      innerAudioContext.play();
      var subject_score = 0;
      wrongs++;
    }


    score += subject_score;
    // score = Math.round(score*100)/100;
    total_time += use_time;
    
    detail.push({ "option_id": option_id, "is_right": is_right, "score": Math.round(subject_score * 100) / 100, "time": use_time });


    
    
    // console.log(start);
    // console.log(end);
    // console.log(use_time);
    // console.log(total_time);
    // console.log(rights);
    // console.log(wrongs);
    // console.log(detail);
    console.log(this.countTimer)
  
    this.setData({
      _state: 1,
      _index: _index,
      score: Math.round(score * 100) / 100,
      total_time: parseInt(total_time/1000),
      accuracy: Math.round(rights / this.data.pagecount * 100),
     
    })

    i++;
    if(this.data.pagenum == this.data.pagecount){
      this.waitTimer = setTimeout(() => {that.submit()}, 1000);
    }else{
      this.waitTimer = setTimeout(() => { that.resetExam(i)}, 1000);
    }
    
  },

  // 答题点击时触发事件
  onSelected: function (e) {
    if(this.data._state == 0){
      this.answerSub(e);
    }

  },

  //显示图片
  showImage:function(){
    let that=this;
    wx.previewImage({
      // current: app.globalData.domain + '/static/xcx/image_open.png',
      urls: [app.globalData.domain+'/'+that.data.subject_photo] // 需要预览的图片http链接列表
    })

  },

  

  // 刷新题目 
  resetExam: function (i){
    console.log(i)
    let that = this; 
    clearTimeout(this.waitTimer);
    if (exam.subjects.length){
      stand_time = exam.subjects[i].timelimit * 10;
      that.setData({
        _state: 0,
        subject_title: exam.subjects[i].title,
        subject_photo: exam.subjects[i].photo,
        options: exam.subjects[i].options,
        num: (exam.subjects[i].timelimit) * 10,
        pagenum: i + 1,
        pagecount: exam.subjects.length,
      });
      console.log(that.data.pagenum)
      console.log(that.data.pagecount)
      
      //选项字体样式
      for(var i=0;i<this.data.options.length;i++){
        var answer_length = this.data.options[i].answer.length
        var answer_style = "middle"        
        if (answer_length>10){
          answer_style = "small" 
        }
        if (answer_length <=8){
          answer_style = "big" 
        }
        that.setData({
          ['options[' + i + '].viewstyle']: answer_style+"view"
        })
      }
      // console.log('kkkk',this.data.options)
      that.drawProgressbg();
      that.countInterval();
      
      start = new Date().getTime();
      console.log(this.countTimer)

    }else{
      that.setData({
        answer_display: "none",
        error_display: "block"
      })
    } 
   
  },


  //获取数据
  getData: function () {
    var that = this
    //调用接口
    wx.request({
      url: app.globalData.domain + '/api/subject/sel', //所有试卷接口地址
      method: 'POST',
      data: {
        exam_id: exam_id
      },
      success: function (res) {
        if (res.data.code > 0) {
          exam = res.data.data
          that.setData({
            exam : exam
          })
          console.log(exam);
          that.numInterval(3);
             
          // that.setData({
          //   exam: res.data.data,
          // });
        }
        else {
          wx.showToast({ title: '暂无试卷' });
        }
      },
      fail: function (res) {
        wx.showToast({ title: '数据加载失败' });
      }
    })
  },

  // 提交函数 

  submit: function(){
    let that = this;
    that.setData({
      answer_display: "none",
      result_display: "block",
    })
    // that.numberGrow()
    app.getUserOpenId(function (err, skey) {
      if(!err){
        wx.request({
          url: app.globalData.domain + '/api/record/add',
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            "exam_id": exam.id,
            "skey":skey,
            "nickname": encodeURI(app.globalData.user.nickName),
            "avatarurl": app.globalData.user.avatarUrl,
            "score": Math.round(score*100,0),
            "totaltime": total_time,
            "rights": rights,
            "wrongs": wrongs,
            "detail": JSON.stringify(detail) 
          },
          success: function(res){
            console.log(res)
            if (res.data.code > 0) {
              that.setData({
                success_data:res.data.data,
                record_id: res.data.data.record_id
              })

              if(res.data.data.exam_times>0){
                app.globalData.own_times = res.data.data.times
              }
              if (res.data.data.exam_times < 0) {
                app.globalData.own_times = res.data.data.today_times
              }
              that.setData({
                own_times: app.globalData.own_times

              })

              //
              // wx.request({
              //   url: app.globalData.domain + '/api/member/updatememberrank',
              //   method: 'POST',
              //   header: {
              //     'content-type': 'application/x-www-form-urlencoded',
              //     'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
              //   },
              //   data: {
              //     skey:skey,
              //     exam_id:exam.id
              //   },
              //   success: function (res) {
              //     console.log(res)
              //     if (res.data.code > 0) {
              //       console.log("更新成功")
              //     }      
              //   }
              // })

            }   
          }
        })
      }else{
        console.log('err:', err);
      }
    })
    //此处提交给php
  },

  shareMoments: function(){
    let that = this
    if(!(that.data.record_id > 0)){return;}
    wx.showLoading({
      title: '生成中',
    })
    wx.request({
      url: app.globalData.domain + '/api/wechat/getexamposter',
      method: 'POST',
      data: {
        record_id:that.data.record_id
      },
      success:function(res){
        wx.hideLoading()
        console.log(res)
        if(res.data.code>0){
          wx.previewImage({
            urls: [app.globalData.domain + '/' + res.data.data] // 需要预览的图片http链接列表
          })
        }
        else{
          wx.showModal({
            title: '',
            content: '网络错误，请重试',
            showCancel: false
          })
        }
      },
      fail:function(){
        wx.hideLoading()
      }
    })
  },

  //用户分享
  onShareAppMessage: function () {
    return {
      title: exam.title,
      path: '/pages/exam/exam?exam_id=' + exam_id,
      success: function (res) {
        // 转发成功
      },
      fail: function (res) {
        // 转发失败
      }
    }
  }

})