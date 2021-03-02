namespace NLogTargetTester
{
    partial class Form1
    {
        /// <summary>
        /// Erforderliche Designervariable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Verwendete Ressourcen bereinigen.
        /// </summary>
        /// <param name="disposing">True, wenn verwaltete Ressourcen gelöscht werden sollen; andernfalls False.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Vom Windows Form-Designer generierter Code

        /// <summary>
        /// Erforderliche Methode für die Designerunterstützung.
        /// Der Inhalt der Methode darf nicht mit dem Code-Editor geändert werden.
        /// </summary>
        private void InitializeComponent()
        {
            this.btnExc = new System.Windows.Forms.Button();
            this.txtMsg = new System.Windows.Forms.TextBox();
            this.label1 = new System.Windows.Forms.Label();
            this.label2 = new System.Windows.Forms.Label();
            this.txtTrace = new System.Windows.Forms.TextBox();
            this.txtExc = new System.Windows.Forms.TextBox();
            this.label3 = new System.Windows.Forms.Label();
            this.label4 = new System.Windows.Forms.Label();
            this.lstLevel = new System.Windows.Forms.ComboBox();
            this.txtLog = new System.Windows.Forms.TextBox();
            this.SuspendLayout();
            // 
            // btnExc
            // 
            this.btnExc.Location = new System.Drawing.Point(334, 162);
            this.btnExc.Name = "btnExc";
            this.btnExc.Size = new System.Drawing.Size(119, 49);
            this.btnExc.TabIndex = 4;
            this.btnExc.Text = "AddItem";
            this.btnExc.UseVisualStyleBackColor = true;
            this.btnExc.Click += new System.EventHandler(this.btnExc_Click);
            // 
            // txtMsg
            // 
            this.txtMsg.Location = new System.Drawing.Point(176, 38);
            this.txtMsg.Name = "txtMsg";
            this.txtMsg.Size = new System.Drawing.Size(277, 20);
            this.txtMsg.TabIndex = 0;
            // 
            // label1
            // 
            this.label1.AutoSize = true;
            this.label1.Location = new System.Drawing.Point(23, 38);
            this.label1.Name = "label1";
            this.label1.Size = new System.Drawing.Size(50, 13);
            this.label1.TabIndex = 2;
            this.label1.Text = "Message";
            // 
            // label2
            // 
            this.label2.AutoSize = true;
            this.label2.Location = new System.Drawing.Point(23, 67);
            this.label2.Name = "label2";
            this.label2.Size = new System.Drawing.Size(35, 13);
            this.label2.TabIndex = 2;
            this.label2.Text = "Trace";
            // 
            // txtTrace
            // 
            this.txtTrace.Location = new System.Drawing.Point(176, 64);
            this.txtTrace.Name = "txtTrace";
            this.txtTrace.Size = new System.Drawing.Size(277, 20);
            this.txtTrace.TabIndex = 1;
            // 
            // txtExc
            // 
            this.txtExc.Location = new System.Drawing.Point(176, 90);
            this.txtExc.Name = "txtExc";
            this.txtExc.Size = new System.Drawing.Size(277, 20);
            this.txtExc.TabIndex = 2;
            // 
            // label3
            // 
            this.label3.AutoSize = true;
            this.label3.Location = new System.Drawing.Point(23, 93);
            this.label3.Name = "label3";
            this.label3.Size = new System.Drawing.Size(54, 13);
            this.label3.TabIndex = 2;
            this.label3.Text = "Exception";
            // 
            // label4
            // 
            this.label4.AutoSize = true;
            this.label4.Location = new System.Drawing.Point(23, 119);
            this.label4.Name = "label4";
            this.label4.Size = new System.Drawing.Size(33, 13);
            this.label4.TabIndex = 2;
            this.label4.Text = "Level";
            // 
            // lstLevel
            // 
            this.lstLevel.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
            this.lstLevel.FormattingEnabled = true;
            this.lstLevel.Items.AddRange(new object[] {
            "Trace",
            "Debug",
            "Info",
            "Warn",
            "Error",
            "Fatal"});
            this.lstLevel.Location = new System.Drawing.Point(176, 119);
            this.lstLevel.Name = "lstLevel";
            this.lstLevel.Size = new System.Drawing.Size(277, 21);
            this.lstLevel.TabIndex = 3;
            // 
            // txtLog
            // 
            this.txtLog.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Bottom | System.Windows.Forms.AnchorStyles.Left) 
            | System.Windows.Forms.AnchorStyles.Right)));
            this.txtLog.BackColor = System.Drawing.Color.White;
            this.txtLog.Location = new System.Drawing.Point(12, 243);
            this.txtLog.Multiline = true;
            this.txtLog.Name = "txtLog";
            this.txtLog.ReadOnly = true;
            this.txtLog.Size = new System.Drawing.Size(507, 144);
            this.txtLog.TabIndex = 1;
            // 
            // Form1
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(531, 399);
            this.Controls.Add(this.lstLevel);
            this.Controls.Add(this.label4);
            this.Controls.Add(this.label3);
            this.Controls.Add(this.label2);
            this.Controls.Add(this.label1);
            this.Controls.Add(this.txtLog);
            this.Controls.Add(this.txtExc);
            this.Controls.Add(this.txtTrace);
            this.Controls.Add(this.txtMsg);
            this.Controls.Add(this.btnExc);
            this.Name = "Form1";
            this.ShowIcon = false;
            this.Text = "NLog Webservice Target Tester";
            this.Load += new System.EventHandler(this.Control_Load);
            this.ResumeLayout(false);
            this.PerformLayout();

        }

        #endregion
        private System.Windows.Forms.Button btnExc;
        private System.Windows.Forms.TextBox txtMsg;
        private System.Windows.Forms.Label label1;
        private System.Windows.Forms.Label label2;
        private System.Windows.Forms.TextBox txtTrace;
        private System.Windows.Forms.TextBox txtExc;
        private System.Windows.Forms.Label label3;
        private System.Windows.Forms.Label label4;
        private System.Windows.Forms.ComboBox lstLevel;
        public System.Windows.Forms.TextBox txtLog;
    }
}

