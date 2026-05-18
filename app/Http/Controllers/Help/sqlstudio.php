<?php  

select ROW_NUMBER() OVER 
(ORDER BY [Institute Code],[session_english],students.[Class],[ddbl_id]) as [Sl],division.Division as [Division],district.District as [District],
(select u.Upazila from Upazila u where college.Upazila=u.[Upazila ID]) as [Upazila],students.[Institute Code] as [EIIN],students.[Institute Code] as [Institution ID],college.[Name of Institute] as [Institution Name],
(select s.status from college_status s where college.status=s.[Status ID]) as [Govt./Non Govt.],(select c.[class] from [class] c where c.[Class ID]=students.[Class]) as [Class],(select g.[Gender] from gender g where g.[Gender ID]=students.[Gender]) as [Gender],
convert(nvarchar(50),students.[Applicant's ID]) as [Student ID],[Applicant's Name] as [Student Name],[Father's Name] as [Father Name],[Mother's Name] as [Mother Name],[session_english] as [Session],
(select g.[Group] from [Group] g where g.[Group ID]=students.[HSC Group]) as [Group],
(select (case when students.[HSC Group]='1'  then s.[Stipend Amount Science] else s.[Stipend Amount Other] end) from [stipend] s where s.[Stipend ID]='1') as [Stipend Amount],
(select (case when students.[HSC Group]='1' then s.[Book Science] else s.[Book Other] end) from [stipend] s where s.[Stipend ID]='1') as [Book Purchase Amount],
(select (case when college.status='1' then '0' else s.[Tution Fees] end) from [stipend] s where s.[Stipend ID]='1') as [Tution Fees],
(select (case when students.[HSC Group]='1' then s.[Stipend Amount Science] else s.[Stipend Amount Other] end) + 
(case when students.[HSC Group]='1' then s.[Book Science] else s.[Book Other] end) + 
(case when college.status='1' then '0' else s.[Tution Fees] end) from [stipend] s where s.[Stipend ID]='1') as [Total Amount],
[Applicant's Mobile Bank Account Number] as [Rocket Account No],'' as [Remarks] from students, 
college, district, division where [Academic Session]=(select [Session BEngali] from session where [Session ID]='56') and 
students.class='1' and students.[Institute Code]=college.[Institute Code (HSSP Project)] and 
college.district=district.[District ID] and district.[Division]=division.[Division ID]  order by 
[Sl],[Division],[District],[Upazila],
[Institute Code],[session_english],
students.[Class],[ddbl_id]






SELECT TOP 1 [Applicant's ID]
      ,t1.[Applicant's Name]
      ,t1.[College]
      ,t2.[Name of Institute]
  FROM [stipend_2017_2018].[dbo].[students] as t1
  JOIN [stipend_2017_2018].[dbo].[college] as t2 ON t1.[College] = t2.[Institute ID]
  WHERE t1.[College] = 7399






SELECT TOP 1 [Applicant's ID]
      ,[Applicant's Name]
      ,[College]
      ,[Father's Name]
      ,[Mother's Name]
      ,[Guardian's Name]
      ,[Relation With The Student]
      ,[Relation With The Student Other]
      ,[Gender]
      ,[Marital Status]
      ,[DOB]
      ,[Religion]
      ,[Religion Other]
      ,[Guardian's NID No]
      ,[Guardian Occupation]
      ,[Guardian Occupation Other]
      ,[Guardian Anual Income]
      ,[Quantity of Guardian's Non Agriculture Land Decimal/Satak]
      ,[Quantity of Guardian's Agro Land]
      ,[Total Land Decimal/Satak]
      ,[Location of Guardian's Land]
      ,[Location of Guardian's Land Other]
      ,[Number of Family Members]
      ,[Number of Earning Person]
      ,[Guardian's Mobile No]
      ,[Applicant's Mobile No (If Any)]
      ,[Applicant's Mobile Bank Account Number]
      ,[Present Address]
      ,[Permanent Address]
      ,[Category of Applicant]
      ,[Category2 of Applicant]
      ,[Category2 of Applicant Other]
      ,[Applicant's Photo]
      ,[Year of Admission]
      ,[Academic Session]
      ,[Class]
      ,[Education Board]
      ,[Education Board Other]
      ,[Class Roll]
      ,[HSC Group]
      ,[HSC Group Other]
      ,[SSC/Equivalent Exam Name]
      ,[SSC/Equivalent Institute Name]
      ,[SSC/Equivalent Passing Year]
      ,[SSC/Equivalent Roll Number]
      ,[SSC/Equivalent Registration Number]
      ,[SSC/Equivalent Registration Session]
      ,[SSC/Equivalent Group]
      ,[SSC/Equivalent Group Other]
      ,[SSC/Equivalent GPA]
      ,[SSC/Equivalent Education Board]
      ,[SSC/Equivalent Education Board Other]
      ,[Operator]
      ,[Entry Date]
      ,[Applicant's ID Bengali]
      ,[Ref ID]
      ,[Old]
      ,[Applicant's Name in English]
      ,[Institute Code]
      ,[Student ID]
      ,[Phase]
      ,[old_id]
      ,[old_mig_code]
      ,[ddbl_sl]
      ,[session_english]
      ,[ddbl_id]
  FROM [stipend_2017_2018].[dbo].[students]










SELECT 
t5.[Division],t3.[District],t4.[Upazila],

t1.[Applicant's ID],t1.[Student ID],t1.[Applicant's Name],
t1.[College],t2.[Name of Institute],t1.[Academic Session],t7.[Class],
t6.[Status],

(select (case when t1.[HSC Group]='1'  then s.[Stipend Amount Science] else s.[Stipend Amount Other] end) from [stipend] s where s.[Stipend ID]='2') as [Stipend Amount],
(select (case when t1.[HSC Group]='1' then s.[Book Science] else s.[Book Other] end) from [stipend] s where s.[Stipend ID]='2') as [Book Purchase Amount],
(select (case when t2.status='1' then '0' else s.[Tution Fees] end) from [stipend] s where s.[Stipend ID]='2') as [Tution Fees]
	  
	  
	  
      
  FROM [stipend].[dbo].[students] as t1

  JOIN [stipend].[dbo].[college] as t2 ON t2.[Institute ID] = t1.[College]
  JOIN [stipend].[dbo].[district] as t3 ON t3.[District ID] = t2.[District]
  JOIN [stipend].[dbo].[Upazila] as t4 ON t4.[Upazila ID] = t2.[Upazila]
  JOIN [stipend].[dbo].[division] as t5 ON t5.[Division ID] = t3.[Division]

  JOIN [stipend].[dbo].[college_status] as t6 ON t6.[Status ID] = t2.[Status]
  JOIN [stipend].[dbo].[class] as t7 ON t7.[Class ID] = t1.[Class]

  WHERE t1.[Applicant's ID] = 3827345



SELECT 
t5.[Division],t3.[District],t4.[Upazila],

t1.[Applicant's ID],t1.[Student ID],t1.[Applicant's Name],
t1.[College],t2.[Name of Institute],t1.[Academic Session],t7.[Class],
t6.[Status],

(select (case when t1.[HSC Group]='1'  then s.[Stipend Amount Science] else s.[Stipend Amount Other] end) from [stipend] s where s.[Stipend ID]='2') as [Stipend Amount],
(select (case when t1.[HSC Group]='1' then s.[Book Science] else s.[Book Other] end) from [stipend] s where s.[Stipend ID]='2') as [Book Purchase Amount],
(select (case when t2.status='1' then '0' else s.[Tution Fees] end) from [stipend] s where s.[Stipend ID]='2') as [Tution Fees]
	  
	  
	  
      
  FROM [stipend].[dbo].[students] as t1

  JOIN [stipend].[dbo].[college] as t2 ON t2.[Institute ID] = t1.[College]
  JOIN [stipend].[dbo].[district] as t3 ON t3.[District ID] = t2.[District]
  JOIN [stipend].[dbo].[Upazila] as t4 ON t4.[Upazila ID] = t2.[Upazila]
  JOIN [stipend].[dbo].[division] as t5 ON t5.[Division ID] = t3.[Division]

  JOIN [stipend].[dbo].[college_status] as t6 ON t6.[Status ID] = t2.[Status]
  JOIN [stipend].[dbo].[class] as t7 ON t7.[Class ID] = t1.[Class]

  WHERE t1.[Applicant's ID] IN (SELECT [student_id] FROM [studentlist].[dbo].[StudentID])

  



?>