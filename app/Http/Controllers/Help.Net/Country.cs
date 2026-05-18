using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace WindowsFormsApplication1.DAL
{
    class Country : MyBase 
    {
        public int Id { get; set; }
        public string Name { get; set;}

        public string Search { get; set; }


        public bool Insert()
        {
            MyCommand  = CommandBuilder(@"insert into countries (country) values (@Name)");
            MyCommand.Parameters.AddWithValue("@Name", Name);
            return ExecuteNQ(MyCommand);
        }


        public bool Update()
        {
            MyCommand = CommandBuilder(@"update countries set country = @Name where id = @Id");
            MyCommand.Parameters.AddWithValue("@Id", Id);
            MyCommand.Parameters.AddWithValue("@Name", Name);
            return ExecuteNQ(MyCommand);
        }


        public bool Delete()
        {
            MyCommand = CommandBuilder(@"delete from countries where id = @Id");
            MyCommand.Parameters.AddWithValue("@Id", Id);
            return ExecuteNQ(MyCommand);
        }

        public DataSet Select()
        {

            return ExecuteDataSet(@"select id, country from countries");
        }

        public DataSet SelectById()
        {
            _Id = Id;
            return ExecuteDataSetById(@"select id, country from countries where id = @_Id");
        }


        public DataSet SelectBySearch()
        {
            _Search = Search;
            return ExecuteDataSetBySearch(@"select id, country from countries where country like @_Search");
        }


        public DataSet SelectBySearch1()
        {
            MyCommand = CommandBuilder(@"select id, country from countries");
            if (!string.IsNullOrEmpty(Search))
            {
                MyCommand.CommandText += " where country like @Search ";
                MyCommand.Parameters.AddWithValue("@Search", "%" + Search + "%");
            }
            

            return ExecuteDataSetBySearch1(MyCommand);
        }

        public DataSet SelectById1()
        {
            MyCommand = CommandBuilder(@"select id, country from countries");
            MyCommand.CommandText += " where id = @Id ";
            MyCommand.Parameters.AddWithValue("@Id", Id);
            return ExecuteDataSetById1(MyCommand);
        }



    }
}
