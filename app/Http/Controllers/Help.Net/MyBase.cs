using System;
using System.Collections.Generic;
using System.Data;
using System.Data.SqlClient;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace WindowsFormsApplication1.DAL
{
    class MyBase
    {
        protected int _Id { get; set; }
        protected string _Search { get; set; }

        protected string _error;

        public string Error
        {
            get { return _error; }
        }

        SqlConnection CN = new System.Data.SqlClient.SqlConnection("Data Source=DESKTOP-CR2CBO7;Initial Catalog=DBProject01;Integrated Security=True");

        protected bool Connection()
        {
            try
            {

                CN.Open();
                return true;
            }
            catch (Exception ex)
            {

                _error = ex.Message;
            }

            return false;

        }

        protected SqlCommand MyCommand { get; set;}

        protected bool ExecuteNQ(SqlCommand cmd)
        {
            if (!Connection())
                return false;

            try
            {
                cmd.ExecuteNonQuery();
                return true;
            }
            catch (Exception ex)
            {
                _error = ex.Message;
            }


            return false;
        }


        protected SqlCommand CommandBuilder(string sql)
        {
            SqlCommand cmd = new SqlCommand();
            cmd.Connection = CN;
            cmd.CommandText = sql;
            return cmd;
        }

        protected DataSet ExecuteDataSet(string sql)
        {
            // DataSetBuilder(@"select id, country from countries")
            MyCommand = CommandBuilder(sql);
            SqlDataAdapter da = new SqlDataAdapter(MyCommand);
            DataSet ds = new DataSet();
            da.Fill(ds);

            return ds;
        }

        protected DataSet ExecuteDataSetById(string sql)
        {
            // DataSetBuilderById(@"select id, country from countries where id = @_Id")
            MyCommand = CommandBuilder(sql);
            SqlDataAdapter da = new SqlDataAdapter(MyCommand);
            MyCommand.Parameters.AddWithValue("@_Id", _Id);

            DataSet ds = new DataSet();
            da.Fill(ds);

            return ds;
        }


        protected DataSet ExecuteDataSetBySearch(string sql)
        {
            // DataSetBuilderById(@"select id, country from countries where id = @_Id")
            MyCommand = CommandBuilder(sql);
            SqlDataAdapter da = new SqlDataAdapter(MyCommand);
            MyCommand.Parameters.AddWithValue("@_Search",  "%" + _Search + "%");

            DataSet ds = new DataSet();
            da.Fill(ds);

            return ds;
        }

        protected DataSet ExecuteDataSetBySearch1(SqlCommand cmd)
        {
            SqlDataAdapter da = new SqlDataAdapter(cmd);
            DataSet ds = new DataSet();
            da.Fill(ds);

            return ds;
        }



        protected DataSet ExecuteDataSetById1(SqlCommand cmd)
        {
            SqlDataAdapter da = new SqlDataAdapter(cmd);
            DataSet ds = new DataSet();
            da.Fill(ds);

            return ds;
        }


    }
}
