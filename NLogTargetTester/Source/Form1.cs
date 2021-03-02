using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;


using NLog;
using NLog.Windows;

namespace NLogTargetTester
{
    public partial class Form1 : Form
    {
        static ILogger logger = LogManager.GetCurrentClassLogger();

        public Form1()
        {
            InitializeComponent();
            lstLevel.SelectedIndex = 0;
        }

        private void Control_Load(object sender, EventArgs e)
        {
            var c = LogManager.Configuration;

            var t = new NLog.Windows.Forms.FormControlTarget();
            t.Name = "TextBox";
            t.Append = true;
            t.ControlName = "txtLog";
            t.FormName = "Form1";
            t.Layout = "${date} ${message} | ${exception} | ${stacktrace} ${newline}";


            c.AddTarget(t);
            c.AddRule(LogLevel.Trace, LogLevel.Fatal, t);

            LogManager.Configuration = c;
        }

        private void btnExc_Click(object sender, EventArgs e)
        {
            try
            {
                logger.Log(
                    LogLevel.FromString( lstLevel.SelectedItem.ToString()),
                    txtMsg.Text.Trim(),
                    new MyException(txtExc.Text, txtTrace.Text)
                );
            }
            catch (Exception ex)
            {
                logger.Error(ex);
            }
        }


        public class MyException : Exception
        {
            string mStackTrace = "";

            public MyException(string message, string stackTrace) : base(message)
            {
                mStackTrace = stackTrace;
            }

            public override string StackTrace
            {
                get { return mStackTrace; }
            }
        }
    }
}
