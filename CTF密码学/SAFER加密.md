# SAFER加密

SAFER（Secure And Fast Encryption Routine） 是一种**分组加密算法**。主要设计人著名的密码学家James L.Massey. 它和**DES属于同系列-分组对称加密**，但是它的扩散性更好，处理起来更方便。**核心加密部分由 异或、模256、X和L变换以及PHT变换**。分组密码包括SAFER K-64,SAFER K-128,SAFER SK-64,SAFER SK-128,SAFER SK-40,SAFER+ 和SAFER++。以下代码实现了SAFER K-64和Safer k-128:

