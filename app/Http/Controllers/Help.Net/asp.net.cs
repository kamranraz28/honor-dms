
<connectionStrings>
    <add name="CSDB" connectionString="data source=.; database=studentlist2; user=sa; password=123123; Integrated Security=False;" providerName="System.Data.SqlClient" />
</connectionStrings>

using System.Data.SqlClient;
using System.Configuration;
using System.Data;

protected string CS = ConfigurationManager.ConnectionStrings["CSDB"].ConnectionString;

protected void GetDataType1()
{
    using (SqlConnection con = new SqlConnection(CS))
    {
        string query =
            "select t1.id as [City Id], t2.name as Country, t1.name as City from cities as t1 " +
            "join countries as t2 on t2.id = t1.country_id";

        SqlCommand cmd = new SqlCommand(query, con);
        con.Open();
        using (SqlDataReader rdr = cmd.ExecuteReader())
        {
            GridView1.DataSource = rdr;
            GridView1.DataBind();
        }
    }
}

protected void GetDataType2()
{
    using (SqlConnection con = new SqlConnection(CS))
    {
        con.Open();
        string query =
            "select t1.id as [City Id], t2.name as Country, t1.name as City from cities as t1 " +
            "join countries as t2 on t2.id = t1.country_id";

        SqlDataAdapter da = new SqlDataAdapter(query, con);
        DataSet ds = new DataSet();
        da.Fill(ds);
        GridView1.DataSource = ds;
        GridView1.DataBind();
    }
}


protected void GetDataType3()
{
    using (SqlConnection con = new SqlConnection(CS))
    {
        string query =
            "select t1.id as [City Id], t2.name as Country, t1.name as City from cities as t1 " +
            "join countries as t2 on t2.id = t1.country_id where t1.name like @textSearch";

        SqlCommand cmd = new SqlCommand(query, con);
        //cmd.CommandType = System.Data.CommandType.StoredProcedure;
        cmd.Parameters.AddWithValue("@textSearch", "%" + txtSearch.Text + "%");
        con.Open();
        using (SqlDataReader rdr = cmd.ExecuteReader())
        {
            GridView1.DataSource = rdr;
            GridView1.DataBind();
        }
        
        
    }
}



protected void GetDataType4()
{
    using (SqlConnection con = new SqlConnection(CS))
    {
        con.Open();
        string query =
            "select t1.id as [City Id], t2.name as Country, t1.name as City from cities as t1 " +
            "join countries as t2 on t2.id = t1.country_id where t1.name like @textSearch";

        SqlDataAdapter da = new SqlDataAdapter(query, con);
        //da.SelectCommand.CommandType = CommandType.StoredProcedure;
        da.SelectCommand.Parameters.AddWithValue("@textSearch", "%" + txtSearch.Text + "%");

        DataSet ds = new DataSet();
        da.Fill(ds);
        GridView1.DataSource = ds;
        GridView1.DataBind();
    }
}




protected void GetddlCountry()
{
    using (SqlConnection con = new SqlConnection(CS))
    {
        con.Open();
        string query = "select t1.id, t1.name as Country from Countries as t1";

        SqlDataAdapter da = new SqlDataAdapter(query, con);
        DataSet ds = new DataSet();
        da.Fill(ds);

        
        ddlCountry.DataTextField = "Country";
        ddlCountry.DataValueField = "id";
        ddlCountry.DataSource = ds;// get the data into the list you can set it
        ddlCountry.DataBind();
        ddlCountry.Items.Insert(0, new ListItem("Select Item",""));


    }
}

protected void ddlCountry_SelectedIndexChanged(object sender, EventArgs e)
{
    //Response.Write(" list value : " + ddlCountry.SelectedItem.Value + " = "+ ddlCountry.SelectedItem.Text + " = " + ddlCountry.SelectedValue);
    if (ddlCountry.SelectedItem.Value == "")
    {
        ddlState.Items.Clear();
    }
    else {
        ddlState.Items.Clear();
        int country_id = Convert.ToInt32(ddlCountry.SelectedItem.Value);
        GetddlState(country_id);
        
    }
    
}



protected void GetddlState(int country_id)
{
    using (SqlConnection con = new SqlConnection(CS))
    {
        con.Open();
        string query = "select t1.id, t1.name as State from States as t1 where t1.country_id = @country_id";

        SqlDataAdapter da = new SqlDataAdapter(query, con);
        da.SelectCommand.Parameters.AddWithValue("@country_id", country_id);
        DataSet ds = new DataSet();
        da.Fill(ds);
        if (ddlCountry.SelectedItem.Value != null)
        {

            ddlState.DataTextField = "State";
            ddlState.DataValueField = "id";
            ddlState.DataSource = ds;// get the data into the list you can set it
            ddlState.DataBind();
            ddlState.Items.Insert(0, new ListItem("Select Item", ""));
        }
    }
}









protected void btnSubmit_Click(object sender, EventArgs e)
{
    //Response.Write("Your Name " + txtName.Text + " <br />" + "Your Email " + txtEmail.Text);

    string name = txtName.Text;
    string email = txtEmail.Text;
    int country_id = Convert.ToInt32(ddlCountry.SelectedItem.Value);
    int state_id = Convert.ToInt32(ddlState.SelectedItem.Value);
    int city_id = Convert.ToInt32(ddlCity.SelectedItem.Value);

    using (SqlConnection con = new SqlConnection(CS))
    {
        string query ="insert into Students (name,email,city_id) values (@name,@email,@city_id)";

        SqlCommand cmd = new SqlCommand(query, con);
        //cmd.CommandType = System.Data.CommandType.StoredProcedure;
        cmd.Parameters.AddWithValue("@name", name);
        cmd.Parameters.AddWithValue("@email", email);
        cmd.Parameters.AddWithValue("@city_id", city_id);
        con.Open();
        int count = cmd.ExecuteNonQuery();
        if (count > 0)
        {
            Response.Write("Data Inserted Successfully");
        }
        else
        {
            Response.Write("Data Did Not Inserted Successfully");
        }
        con.Close();


    }



}



string query = "select t1.id as id,t1.name as Student,t1.email as Email,t4.name as Country,t3.name as State,t2.name as City,"+

"convert(varchar(11),t1.created_at,106) as Date, (case when t1.status = 0 then 'Inactive' else 'Active' end) as Status"+
"from Students as t1 join Cities as t2 on t2.id = t1.city_id join States as t3 on t3.id = t2.state_id join Countries as t4 on t4.id = t2.country_id"